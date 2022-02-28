 /* for  edit data */

 /* for showing data   */
 function serverSide_datatable(url, table_id) {
 	$(document).ready(function () {
 		$("#" + table_id).DataTable({
 			retrieve: true,
 			"processing": !0,
 			"ajax": {
 				url: url,
 				type: 'GET',
 			},
 			dom: 'Blfrtip',
 			buttons: [
 				// 'excel', 'print'
 			],
 			"language": {
 				"sProcessing": "جارٍ التحميل...",
 				"sLengthMenu": "أظهر _MENU_ مدخلات",
 				"sZeroRecords": "لم يعثر على أية سجلات",
 				"sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
 				"sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
 				"sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
 				"sInfoPostFix": "",
 				"sSearch": "ابحث:",
 				"sUrl": "",
 				"oPaginate": {
 					"sFirst": "الأول",
 					"sPrevious": "السابق",
 					"sNext": "التالي",
 					"sLast": "الأخير"
 				},
 				"buttons": {
 					"print": "طباعة",
 					"copyKeys": "زر <i>ctrl<\/i> أو <i>⌘<\/i> + <i>C<\/i> من الجدول<br>ليتم نسخها إلى الحافظة<br><br>للإلغاء اضغط على الرسالة أو اضغط على زر الخروج.",
 					"copySuccess": {
 						"_": "%d قيمة نسخت",
 						"1": "1 قيمة نسخت"
 					},
 					"pageLength": {
 						"-1": "اظهار الكل",
 						"_": "إظهار %d أسطر"
 					},
 					"collection": "مجموعة",
 					"copy": "نسخ",
 					"copyTitle": "نسخ إلى الحافظة",
 					"csv": "CSV",
 					"excel": "اكسل",
 					"pdf": "PDF",
 					"colvis": "إظهار الأعمدة",
 					"colvisRestore": "إستعادة العرض"
 				},
 			}
 		}, )
 	});
 }
 //for insert data
 function insertData(modal_id, insert_url, btn_add_id, showdata_url, table_id, messages = {
 	success: 'Data added successfully !',
 	error: "Error occured !"
 }) {
 	$(document).ready(function () {
 		$('#' + btn_add_id).on('click', function () {
 			var username = $('#username').val();
 			var password = $('#password').val();
 			var validity = $('#validity').val();
 			if (username != "" && password != "" && validity != "") {
 				$("#" + btn_add_id).attr("disabled", "disabled");
 				$.ajax({
 					url: insert_url,
 					type: "POST",
 					data: {
 						username: $('#username').val(),
 						password: $('#password').val(),
 						validity: $('#validity').val(),
 					},
 					cache: false,
 					success: function (dataResult) {
 						if (dataResult.success != false) {
 							alert
 								(messages.success);
 						}
 						$('#' + table_id).DataTable().destroy();
 						serverSide_datatable(showdata_url, table_id);
 						$('#validity').val("");
 						$('#password').val("");
 						$('#username').val("");
 						$("#" + btn_add_id).removeAttr("disabled");
 						var dataResult = JSON.parse(dataResult);
 						if (dataResult.statusCode == 200) {
 							// $("#"+form_id).find('input:text').val('');
 							$("#" + success_div_id).html(dataResult);
 						} else if (dataResult.statusCode == 201) {
 							alert
 								(messages.error);
 						}
 					}
 				});
 			}
 			//     else {
 			//         alert('Please fill all the field !');
 			//  }
 		});
 	});
 }
 /* for update data  */
 function updateData(edit_modal_id, insert_url, edit_add_id, showdata_url, table_id, messages = {
 	success: 'Data updated successfully !',
 	error: "Error occured !"
 }) {
 	$('#' + edit_add_id).on('click', function () {
 		var username = $('#username_edit').val();
 		var password = $('#password_edit').val();
 		var validity = $('#validity_edit').val();
 		if (username != "" && password != "" && validity != "") {
 			$("#" + edit_add_id).attr("disabled", "disabled");
 			$.ajax({
 				url: insert_url,
 				type: "POST",
 				data: {
 					username: $('#username_edit').val(),
 					password: $('#password_edit').val(),
 					validity: $('#validity_edit').val(),
 					id: $('#' + edit_add_id).val()
 				},
 				cache: false,
 				success: function (dataResult) {
 					if (dataResult.success != false) {
 						alert
 							(messages.success);
 					}
 					$('#' + table_id).DataTable().destroy();
 					serverSide_datatable(showdata_url, table_id);
 					$('#validity_edit').val("");
 					$('#password_edit').val("");
 					$('#username_edit').val("");
 					$('#' + edit_modal_id).modal('hide');

 					$("#" + edit_add_id).removeAttr("disabled");
 					var dataResult = JSON.parse(dataResult);
 					if (dataResult.statusCode == 200) {
 						$("#" + success_div_id).html(dataResult);
 					} else if (dataResult.statusCode == 201) {
 						alert
 							(messages.error);
 					}
 				}
 			});
 		}
 		//     else {
 		//         alert('Please fill all the field !');
 		//  }
 	});
 }


 /* FOR delete data */

 function deleteData(delete_url,showdata_url, table_id, messages = {
	success: 'Data deleted successfully !',
	error: "Error occured !"
,
confirmdelete:"هل أنت متأكد من الحذف"
}) {
	$(document).on('click', '.delete_btn', function(){  
		var id = $(this).attr("id");  
		if(confirm(messages.confirmdelete))  
		{  
			 $.ajax({  
				  url:delete_url,  
				  method:"POST",  
				  data:{id:id},  
				  success:function(data)  
				  {  
					alert(messages.success);  

					$('#' + table_id).DataTable().destroy();
					serverSide_datatable(showdata_url, table_id);
 				  }  
			 });  
		}  
		else  
		{  
			 return false;       
		}  
   });  
 }
 /* for edit  */
 function edit(edit_url, modal_id, edit_save_btn_id) {
	$(document).on('click', '.update_btn', function () {
		var id = $(this).attr("id");
		$.ajax({
			url: edit_url,
			method: "POST",
			data: {
				id: id
			},
			dataType: "json",
			cache: false,
			success: function (dataResult) {

				/* setting st art */
				$('#username_edit').val(dataResult[0].username);
				$('#password_edit').val(dataResult[0].password);
				$('#validity_edit').val(dataResult[0].validity);
				/* for select options edit */
				if (dataResult[0].validity == 1) {
					$('select option:contains("مستخدم عادي")').removeAttr('selected');
					$('select option:contains("مدير")').attr('selected', 'selected');
				} else {
					$('select option:contains("مدير")').removeAttr('selected');
					$('select option:contains("مستخدم عادي ")').attr('selected', 'selected');
				}
				$('#' + modal_id).modal('show');
				//   $('.modal-title').text("Edit User");  
				$('#' + edit_save_btn_id).val(id);
				/* setting end */

			}
		})
	});
}