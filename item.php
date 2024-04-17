<style>
		.panel > .panel-hdr{
			display: flex;
		}

		.panel .panel-hdr h2{
		    height: 25px;
    		padding-top: 10px;
		}
		
		.panel .panel-hdr > h2 {
			padding-left: 10px;
			min-width: 86px;
		}
		
		button#item_btnAdd {
			float: right;
		}
		
		.panel-hdr > :last-child {
			padding-right: 0rem!important;
		}

	@media screen and (max-width: 533px) {
		.panel > .panel-hdr{
			display: block;
		}

		.panel .panel-hdr h2{
		    height: 25px;
    		padding-top: 10px;
		}
		
		button#item_btnAdd {
			float: right;
		}
		
		ul#ItemTabHrv {
			margin-left: unset;
		}
</style>

<div class="panel">
	<div class="panel-hdr">
		<h2> Item </h2>
		<div class="form-inline float-right">
			<ul class="nav nav-tabs panel-hdr" id="ItemTabHrv" role="tablist">
				<li class="nav-item">
					<div class="custom-checkbox custom-control custom-control-inline">
						<input id="item_cbDeactive" type="checkbox" class="custom-control-input">
						<label class="custom-control-label" for="item_cbDeactive">Show All</label>
					</div>
				</li>
				<li class="nav-item">
					<div class="form-group input-group mr-2">
						<div class="form-group-prepend">
							<span class="btn btn-sm btn-primary"><i class="fal fa-search"></i></button>
						</div>
						<input id="item_tblSearch" type="text" class="form-control h-75" placeholder="Search" aria-label="Raw Data Config" aria-describedby="button-addon5">
					</div>
				</li>
				<li class="nav-item">
					<div class="form-group">
						<button type="button" id="item_btnAdd" class="btn btn-primary btn-sm btn-space waves-effect waves-themed ml-1"><i class="fal fa-plus"></i></button>
						<div id="sparepartDw" class="dropdown-menu dropdown-menu-animated dropdown-menu-right" style=""></div>
						<button id="item_btnRefresh" type="button" onclick="item_get_data($(this));" class="btn btn-sm btn-primary btn-space waves-effect waves-themed float-right ml-1"><i class="fal fa-sync"></i></button>
					</div>
				</li>

			</ul>
		</div>
	</div>

	<div class="panel-container show">
		<div class="panel-content p-2">
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade show active" id="crm_item_table_tab" role="tabpanel">
					<div id="item_table" class="chart-large txt-color-blue w-100" style="height: 700px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="item_modal_form" class="modal fade show" role="dialog" aria-hidden="true" style="overflow: auto !important;">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom p-3">
				<h4 class="modal-title"> <span id="item_form_title"> </span> Item </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="item_form">
					<input type="hidden" id="item_form_action" name="action" value="add">
					<input type="hidden" id="itemId" name="itemId" value="">

					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 mb-2">
							<div class="form-group">
								<label class="form-label ">Name </label>
								<input type="text" id="item_name" name="itemName" class="form-control" placeholder="Item Name" required>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 mb-2">
							<div class="form-group">
								<label class="form-label ">Description </label>
								<input type="text" id="item_desc" name="itemDesc" class="form-control" placeholder="Item Description" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 mb-2">
							<div class="form-group">
								<label class="form-label ">Category </label>
								<select id="item_cate" name="item_cate" class="custom-select" required>
									<option value="" disabled selected="selected">Select Category</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 mb-2">
							<div class="form-group">
								<label class="form-label ">Price </label>
								<input type="number" id="item_price" name="itemPrice" class="form-control" placeholder="Item Price" pattern="(\d{0}|\d{1}|\d{2}|\d{3}|\d{4}|\d{5})" maxlength="9" required>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm  btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
				<button type="button" id="item_btnSubmit" class="btn btn-primary btn-sm waves-effect waves-themed">Submit</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var cateData;
	
	var pagefunction = function() {
		loadScript(base_url + "/public/js/moment.js?jc=" + jCode, function() {
			loadScript(base_url + "/public/js/tabulator/jspdf.min.js?jc=" + jCode, function() {
				loadScript(base_url + "/public/js/bootstrap/bootstrap-multiselect.js?jc=" + jCode, function() {
					loadScript(base_url + "/public/js/item.js?jc=" + jCode, page_init); 
				});
			});
		});
		
		function page_init() {
			init_change_item();
			update_item_table();
			item_get_data($('#item_btnRefresh'));
			
			$('#item_btnAdd').click(() => {
				$('#item_form_title').text("Add");
				$('#item_form').trigger("reset");
				reset_validation('item_form');

				$('#item_form_action').val("add");
				$('#item_form_id').val("-1");
				$("#item_modal_form").modal({
					keyboard: true
				});
			});
			
			
			$("#item_tblSearch").keyup(function() {
				var active = $('#item_cbDeactive').is(":checked");
				var rText = $('#item_tblSearch').val();
				_itemTable_['myTable'].setFilter(filerTable, {
					'value': rText,
					'showall': active
				});
			});
			
			$("#item_form").validate({
				rules: {
					itemName: {
						required: true
					},
					itemDesc: {
						required: true
					},
					item_cate: {
						required: true
					},
					itemPrice: {
						required: true
					},

				},
				messages: {
					itemName: {
						required: 'Please Enter Item Name',
					},
					itemDesc: {
						required: 'Please Enter Description',
					},
					item_cate: {
						required: 'Please Select Item Category',
					},
					itemPrice: {
						required: 'Please Enter Item Price',
					},
				}
			});
			
			
			$('#item_btnSubmit').click(() => {
				if (!$('#item_form').valid()) return;

				$('#item_btnSubmit').prop('disabled', true);
				$('#item_btnSubmit').text('Please wait..');

				let postData = $('#item_form').serializeArray();
				var itData = {};
				for (i in postData) {
					itData[postData[i].name] = postData[i].value;
				}

				$.ajax({
					url: base_url + "/Inventory/Item/Save_item",
					method: "post",
					dataType: "json",
					data: {
						'item_id': itData.item_id,
						'action': itData.action,
						'itData': itData
					},
					success: function(response) {
						if (response._status_ === 'success') {
							item_get_data($('#item_btnRefresh'), $('#item_form_modelDevice').find(":selected").val());
						}
						$('#item_btnSubmit').text('Submit');
						$('#item_btnSubmit').prop('disabled', false);
					},
					error: function(e) {
						$('#item_btnSubmit').text('Submit');
						$('#item_btnSubmit').prop('disabled', false);
					}
				});

				$('#item_modal_form').modal('hide');
			});
		}
	}
	pagefunction();
</script>