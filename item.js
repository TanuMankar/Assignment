var _itemTable_ 		= 0;
var _itemTableOpts_		= 0;
var _itemTableData_		= 0;

var itmActionWidget = function(cell, data, value, row, options){

	cell.getElement().style.overflow= 'initial';
	var itmData			= cell.getRow().getData();
	var statIcon 		= (itmData.active == 0)? "fa-check" 	: "fa-unlink";
	var actionBtn		= '';
	var rowEle  		= cell.getRow().getElement();

	if (invAuth.isEdit)   actionBtn += '<a class="dropdown-item pt-1 pb-1 ' +disabledStat+ '" href="javascript:void(0)" onclick="item_edit(\'' +(itmData.item_id)+ '\')"><i class="fal fa-edit"></i> Edit </a>';
	if (invAuth.isDelete) actionBtn += '<a class="dropdown-item pt-1 pb-1" href="javascript:void(0)" onclick="item_change_status(\'' +itmData.slno+ '\')"><i class="fal ' +statIcon+ ' text-danger"></i> ' +title+ ' </a>'; 

	return '<div class="btn-xs btn-group">' +
				'<button type="button" class="btn btn-xs btn-outline-secondary dropdown-toggle waves-effect waves-themed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
					'<i class="fal fa-cog"></i>' +
					' Action ' +
				'</button>' +
				'<div class="dropdown-menu">' +
					actionBtn +
				'</div>' +
			'</div>';
};

function init_change_item() {
	_itemTable_ = {
		'myTable'		: 0,
		'autoLayOut'	: true,
		'redraw'		: true,
		'myTableDiv'	: "#item_table",
	};
	_itemTableOpts_ = {
		'structure'	: {
				'slno'	: {
				'cOpts'	: {'title' : 'No.', 'minWidth' : 40, formatter:"rownum"},
				'pl'		: 'slno',
				'dv' 		: 1,
				'pSource'	: ['xc2'],
		   },
				'item_id'		: {
				'cOpts'	: {'title' : 'No.', 'width' : 80, 'hozAlign': 'center', visible:false},
				'pl'		: '',
				'dv'		: 1,
				'pSource'	: ['xc2'],
			},
			'itemName': {
				'cOpts'	: {'title' : 'Product Name', 'minWidth' : 150, 'hozAlign': 'left'},
				'pl'		: '',
				'dv'		: '',
				'pSource'	: ['p'],
			},
			'itemDesc': {
				'cOpts'	: {'title' : 'Product Description', 'minWidth' : 150, 'hozAlign': 'left'},
				'pl'		: '',
				'dv'		: '',
				'pSource'	: ['p'],
			},
			'item_cat_name'	: {
				'cOpts'	: {'title' : 'Category', 'minWidth' : 150, 'hozAlign': 'left'},
				'pl'		: '',
				'dv'		: '',
				'pSource'	: ['p'],
			},
			'itemPrice'	: {
				'cOpts'		: {'title' : 'Product Part Price', 'minWidth' : 150, 'hozAlign': 'right'},
				'pl'		: '',
				'dv'		: '',
				'pSource'	: ['p'],
			},
			'action'	: {
				'cOpts'		: {'title' : 'Action', 'width' : 100, 'formatter': itmActionWidget},
				'pl'		: 'action',
				'dv'		: 'No Action',
				'pSource'	: ['x'],
			},
		},
		'paramList'		: {},
	};
}

function update_item_table( options = {}) {
	$.extend(true, _itemTable_, options);
	if(_itemTable_['myTableDiv'] <= 0) return;

	function init_item_table(){
		if(_itemTable_['redraw']) {

			if(_itemTable_['myTable'] != 0) _itemTable_['myTable'].destroy();

			_itemTable_['myTable'] = new Tabulator(_itemTable_['myTableDiv'], {
				height                	: 600,
				layout                	: ((_itemTable_['autoLayOut'])? "fitDataFill" : "fitColumns"),
				pagination     			: "local",
				paginationSize 			: 18,
				tooltips              	: true,
				movableRows           	: false,
				movableColumns        	: false,
				placeholder           	: "No Data Available",
				columnHeaderVertAlign 	: "top",
				//responsiveLayout 		: "collapse",
				columns               	: get_table_header(_itemTableOpts_['structure'], _itemTableOpts_['paramList']),
			});
			
			var cols = _itemTable_['myTable'].getColumns();
			var w    = 0;

			_itemTable_['redraw'] = false;
		}
	}

	init_item_table();

	_itemTable_['myTable'].setData(_itemTableData_);
	_itemTable_['myTable'].setFilter("active", (">" + (deactive ? "=" : "")), 0);
}

function item_get_data(btnElem) {
	btnElem.prop('disabled', true);

	$.ajax({
		url			: base_url +"/Item/Get_item_data",  
		method		: "post",
		data		: {},
		dataType	: "json",
		async       : false,   
		success	    : function (response){
			if (response._status_ === 'success') {
				_itemTableData_ = response.data;
				update_item_table({'autoLayOut' : true, 'redraw' : true});
				$("#item_tblSearch").trigger('keyup');
			}
			btnElem.prop('disabled', false);
		},
		error: function (e){
			btnElem.prop('disabled', false);
		}
	});
}

function item_edit(id) {
	
	$('#item_form_title').text("Edit");
	reset_validation('item_form');
	$('#item_form').trigger("reset");
	$('#item_form_action').val("edit");
	$('#itemId').val(getRowData.item_id);
	$('input#item_name').val(getRowData.itemName);
	$('input#item_desc').val(getRowData.itemDesc);
	$('#item_cate').val(getRowData.item_cate).trigger('change');  
	$('#item_price').val(getRowData.itemPrice);

	$('#item_modal_form').modal({keyboard: true});

}