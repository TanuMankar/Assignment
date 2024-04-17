<style>
		.panel > .panel-hdr{
			display: flex;
		}

		.panel .panel-hdr h2{
		    height: 25px;
    		padding-top: 10px;
		}
		
		button#item_btnAdd {
			float: right;
		}
		
		li#dwnBtn {
			position: unset;
			margin-bottom: 1px;
		}
				
</style>

<div class="panel">
	<div class="panel-hdr">
		<h2> Inventory & Settings </h2>
		<div class="form-inline float-right">
			<div class="panel-toolbar ml-2"> 
				<div class="custom-checkbox custom-control custom-control-inline">
					<input id="inv_cbDeactive" type="checkbox" class="custom-control-input" >
					<label class="custom-control-label" for="inv_cbDeactive">Show All</label>
				</div>
            </div>
			<div class="form-group allInvSearch input-group mr-2" >
				<div class="form-group-prepend" id="has_cat_tblSearch">
					<span id="item_cat_tblSearch" class="btn btn-sm btn-primary" ><i class="fal fa-search"></i></span>
					<input id="item_tblSearch" type="text" class="form-control " placeholder="Search" aria-label="" aria-describedby="button-addon5">
				</div>

				<div class="form-group-prepend" id="has_loc_tblSearch">
					<span id="locationSearchButton" class="btn btn-sm btn-primary" ><i class="fal fa-search"></i></span>
					<input id="item_location_tblSearch" type="text" class="form-control " placeholder="Search" aria-label="Raw Data Config" aria-describedby="button-addon5">
				</div>
			</div>
			<div id="btnPositionSetter" class="form-group">
                <div class="d-flex" id="allAddInvBtn"> 
                    <button type="button" class="btn btn-primary btn-sm btn-space waves-effect waves-themed ml-1" id="item_cat_btnAdd" 	 onclick="changeTab('item_cat_table');"><i class="fal fa-plus"></i></button>
                    <button type="button" class="btn btn-primary btn-sm btn-space waves-effect waves-themed ml-1" id="item_loc_btnAdd" 	 onclick="changeTab('Location_listing');" style="display:none"><i class="fal fa-plus"></i></button>
                </div>
			</div>
		</div>
	</div>
	<div class="panel-container show">
		<div class="panel-content p-0">
			<ul class="nav nav-tabs panel-hdr IntTabHrv rt_inventory_sett" id="IntTabHrv" role="tablist">
				<li class="nav-item">
					<a class="nav-link color-success-700 active" onclick="changeTab('item_cat_table');" href="#categorytab"; data-toggle="tab">Category</a>
				</li>
				<li class="nav-item">
					<a class="nav-link color-success-700" onclick="changeTab('Location_listing');" href="#locationtab"; data-toggle="tab">Location</a>
				</li>
			</ul>
			<div class="tab-content border border-top-0 p-2">
				<div class="tab-pane fade show active" id="categorytab" role="tabpanel">
					<?php include_once 'item_category.php';?>
				</div>
				<div class="tab-pane fade" id="locationtab" role="tabpanel">
					<?php include_once 'item_location.php';?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- add Model for Category -->
<div id="item_cat_modal_form" class="modal fade show" role="dialog" aria-hidden="true" style="overflow: auto !important;">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom p-3">
				<h4 class="modal-title"> <span id="item_cat_form_title"></span> Category </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="item_cat_form">
					<input type="hidden" id="item_cat_form_action" name="action"      value="add">
					<input type="hidden" id="item_catId"           name="item_catId"  value="">

					 <div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 mb-2">
							<div class="form-group">
								<label class="form-label"> Category Name </label> 
								<input type="text" id="item_cat_name" name="item_cat_name" class="form-control" placeholder="Item Category Name" required>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm  btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
				<button type="button" id="item_cat_btnSubmit" class="btn btn-primary btn-sm waves-effect waves-themed">Submit</button>
			</div>
		</div>
	</div>
</div>

<div id="item_loc_modal_form" class="modal fade show" role="dialog" aria-hidden="true" style="overflow: auto !important;">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom p-3">
				<h4 class="modal-title"> <span id="item_loc_form_title"></span> Location </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="item_loc_form">
					<input type="hidden" id="item_loc_form_action" name="action"      value="add">
					<input type="hidden" id="item_locId"           name="item_locId"  value="">

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 mb-2">
							<div class="form-group">
								<label class="form-label"> Location Name </label>
								<input type="text" id="item_loc_name" name="item_loc_name" class="form-control" placeholder="Item Location Name" required>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm  btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
				<button type="button" id="item_loc_btnSubmit" class="btn btn-primary btn-sm waves-effect waves-themed">Submit</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

var catData         = [];

	var pagefunction = function () {
		loadScript(base_url + "/public/js/bootstrap/bootstrap-multiselect.js?jc?jc="+jCode, page_init);
		
		function page_init() {
			init_cat_change_item();
			update_item_cat_table();
			item_cat_get_data($('#inv_btnRefresh'));
			
			$('#item_cat_btnAdd').click(() => {
				$('#item_cat_form_title').text("Add");
				$('#item_cat_form').trigger("reset");
				reset_validation('item_cat_form');
				$('#item_cat_form_action').val("add");
				$("#item_cat_modal_form").modal({ keyboard: true });
			});
			
			$('#item_loc_btnAdd').click(() => {
				$('#item_loc_form_title').text("Add");
				$('#item_loc_form').trigger("reset");
				reset_validation('item_loc_form'); 
				$('#item_loc_form_action').val("add");
				$("#item_loc_modal_form").modal({ keyboard: true });
			});
			
			$("#item_tblSearch").keyup(function() {
				var active	= $('#inv_cbDeactive').is(":checked");
				var rText	= $('#item_tblSearch').val();
				_itemcatTable_['myTable'].setFilter(filerTable, {'value': rText, 'showall': active});
			});
			
			$("#item_location_tblSearch").keyup(function() {
				var active	= $('#inv_cbDeactive').is(":checked");
				var rText	= $('#item_location_tblSearch').val();
				_itemlocTable_['myTable'].setFilter(filerTable, {'value': rText, 'showall': active});
			});
			
		}
		
		function catTableUpdate(){
            init_cat_change_item() 
			update_item_cat_table(0);
        }
		
		function locTableUpdate(){
            init_loc_change_item();
			update_item_loc_table(0);
        }
		
		function init_category_data() {
		$.ajax({
			url			: base_url +"/Inventory_setting/Get_cat_item_data",
			method		: "post",
			data		: {},
			dataType	: "json",
			success	: function (response){
				if (response._status_ === 'success') {
					var catData = response.data;
					for (catId in catData) {
						$('#item_cat_insub_name').append("<option value='"+catData[catId]['item_cat_id']+"'>"+catData[catId]['item_cat_name']+"</option>"); 
					}
				}
			},
			error: function (e){
			}
		});
	}
	}
</script>