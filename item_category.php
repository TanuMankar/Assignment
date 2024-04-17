<div id="item_cat_table" class="chart-large txt-color-blue w-100"></div>

<script type="text/javascript">

var _itemcatTable_ 			= 0;
var _itemcatTableOpts_		= 0;
var _itemcatTableData_		= 0;

$("#item_cat_form").validate(
{
	rules:
	{
		item_cat_name 		: {required: true},

	},
	messages:
	{
		item_cat_name   :
		{
			required    :'Please Enter Category Name',
		},
	}
});

var drCatActionWidget = function(cell, data, value, row, options){

	cell.getElement().style.overflow= 'initial';
	var rData			= cell.getRow().getData();
	var cField			= cell.getField();
	var statIcon 		= (rData.active == 0)? "fa-check" 	: "fa-unlink";
	var title 			= (rData.active == 0)? "Activate " 	: "Deactivate ";
	var disabledStat 	= (rData.active == 0)? "disabled "   : "";
	var actionBtn		= '';
	var rowEle  		= cell.getRow().getElement();

	if (invAuth.isEdit)   actionBtn += '<a class="dropdown-item pt-1 pb-1 ' +disabledStat+ '" href="javascript:void(0)" onclick="category_edit(\'' +(rData.item_cat_id)+ '\')"><i class="fal fa-edit"></i> Edit </a>';
	if (invAuth.isDelete) actionBtn += '<a class="dropdown-item pt-1 pb-1" href="javascript:void(0)" onclick="item_cat_change_status(\'' +rData.slno+ '\')"><i class="fal ' +statIcon+ ' text-danger"></i> ' +title+ ' </a>';

	return '<div class="btn-xs btn-group">' +
				'<button type="button" class="btn btn-xs btn-outline-secondary dropdown-toggle waves-effect waves-themed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
					'<i class="fal fa-cog"></i>' +' Action ' +'</button>' +'<div class="dropdown-menu">' + actionBtn +'</div>' +
			'</div>';
};

function init_cat_change_item() {
	_itemcatTable_ = {
		'myTable'		: 0,
		'autoLayOut'	: true,
		'redraw'		: true,
		'myTableDiv'	: "#item_cat_table",
	};
	_itemcatTableOpts_ = {
		'structure'	: {
			'slno'	: {
				'cOpts'		: {'title' : 'No.', 'width' : 80, 'minWidth' : 40, 'hozAlign': 'center', formatter:"rownum"},
				'pl'		: '',
				'dv' 		: 1,
				'pSource'	: ['xc2'],
		   },
			'item_cat_id'		: {
				'cOpts'		: {'title' : 'Sr No', 'width' : 80, 'hozAlign': 'left', visible: false},
				'pl'		: '',
				'dv'		: 1,
				'pSource'	: ['xc2'],
			},
			'item_cat_name': {
				'cOpts'		: {'title' : 'Category name', 'minWidth' : 150, 'hozAlign': 'left'},
				'pl'		: '',
				'dv'		: '',
				'pSource'	: ['p'],
			},
			'action'	: {
				'cOpts'		: {'title' : 'Action', 'width' : 100, 'formatter': drCatActionWidget},
				'pl'		: 'action',
				'dv'		: 'No Action',
				'pSource'	: ['x'],
			},
		},
		'paramList'		: {},
	};
}

function update_item_cat_table( options = {}) {
	$.extend(true, _itemcatTable_, options);
	if(_itemcatTable_['myTableDiv'] <= 0) return;
	var deactive = (($('#inv_cbDeactive').is(":checked")) ? 1 : 0);

	function init_cat_item_table(){
		if(_itemcatTable_['redraw']) {

			if(_itemcatTable_['myTable'] != 0) _itemcatTable_['myTable'].destroy();

			_itemcatTable_['myTable'] = new Tabulator(_itemcatTable_['myTableDiv'], {
				height                	: 600,
				layout                	: ((_itemcatTable_['autoLayOut'])? "fitDataFill" : "fitColumns"),
				pagination     			: "local",
				paginationSize 			: 18,
				tooltips              	: true,
				movableRows           	: false,
				movableColumns        	: false,
				placeholder           	: "No Data Available",
				columnHeaderVertAlign 	: "top",
				//responsiveLayout 		: "collapse",
				columns               	: get_table_header(_itemcatTableOpts_['structure'], _itemcatTableOpts_['paramList']),
			});

			var cols = _itemcatTable_['myTable'].getColumns();
			var w    = 0;
			
			_itemcatTable_['redraw'] = false;
		}
	}
	init_cat_item_table();

	for(let single_Data in _itemcatTableData_) {
		_itemcatTableData_[single_Data].logTime = formatDate(new Date(_itemcatTableData_[single_Data].dc_logtime*1000).getTime(),longDateFormat);
	}
	_itemcatTable_['myTable'].setData(_itemcatTableData_);
	_itemcatTable_['myTable'].setFilter("active", (">" + (deactive ? "=" : "")), 0);
}

function item_cat_get_data(btnElem) {
	btnElem.prop('disabled', true);

	$.ajax({
		url			: base_url +"/Inventory_setting/Get_cat_item_data",  
		method		: "post",
		data		: {},
		dataType	: "json",
		success	: function (response){
			if (response._status_ === 'success') {
				_itemcatTableData_ = response.data;
				update_item_cat_table({'autoLayOut' : true, 'redraw' : true});
			
			}
			btnElem.prop('disabled', false);
		},
		error: function (e){
			btnElem.prop('disabled', false);
		}
	});
}

$('#item_cat_btnSubmit').click(() => {

	if(!$('#item_cat_form').valid()) return;
	if(!hasFormChanged(document.getElementById('item_cat_form'))){
		toastr.warning('No change in data', 'warning');
		return;
	}; 

	$('#item_cat_btnSubmit').prop('disabled',true);
	$('#item_cat_btnSubmit').text('Please wait...');

	let postData = $('#item_cat_form').serializeArray();
	reset_validation('item_cat_form'); 	

	var catData = {};
	for(i in postData) {
		catData[postData[i].name]= postData[i].value;
	}

    $.ajax({
		url			: base_url +"/Inventory_setting/Save_cat_item",  
		dataType	: "json",
		data		: {'item_cat_id': catData.item_catId, 'action': catData.action, 'catData' : catData},
		success	: function (response){
			if (response._status_ === 'success') {
			
				item_cat_get_data($('#item_btnRefresh'));
			}
			$('#item_cat_btnSubmit').text('Submit');
			$('#item_cat_btnSubmit').prop('disabled', false);
		},
		error: function (e){
			$('#item_cat_btnSubmit').text('Submit');
			$('#item_cat_btnSubmit').prop('disabled', false);
		}
	});

	$('#item_cat_modal_form').modal('hide');
});

function category_edit(id) {

	let getRowData = {};
	for(let index in _itemcatTableData_) if(_itemcatTableData_[index].item_cat_id == id) { getRowData = _itemcatTableData_[index]; break; }

	$('#item_cat_form_title').text("Edit");
	$('#item_cat_form').trigger("reset");
	reset_validation('item_cat_form'); 	
	$('#item_cat_form_action').val("edit");
	$('#item_catId').val(getRowData.item_cat_id);
	$('#item_cat_name').val(getRowData.item_cat_name);

	$('#item_cat_modal_form').modal({keyboard: true});	
}
