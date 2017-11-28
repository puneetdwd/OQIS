 $(document).ready(function() {

 
	//Start Inspection Name by inspection Type
	$('#insp-type').change(function() {
        
        App.blockUI({
            target: '.portlet',
            boxed: true
        }); 
        
        var insp_type = $('#insp-type :selected').val();
        //alert(insp_type);exit;
        $.ajax({
            type: 'POST',
            url: base_url+'inspections/get_inspection_by_type',
            data: { insp_type: insp_type},
            dataType: 'json',
            success: function(resp) {
                /* if($('#inspection_id :selected').val() != '') {
					$('#inspection_id').select2('val', null);
				} */
                
                $('#inspection_id').html('');
                
                $('#inspection_id').append('<option value=""></option>');
                $.each(resp.inspections, function (i, item) {
                    $('#inspection_id').append($('<option>', { 
                        value: item.id,
                        text : item.name, 
                    }));
                });
               App.unblockUI('.portlet');
            }
        }); 
    });
	//End Inspection Name by inspection Type
	
    var base_url = $('#base_url').val();
    
    $('.dashboard-progress-section').load(base_url+'dashboard/show_day_progress');
    
    if($('#existing_checkpoints').length > 0) {
        $('#add-checkpoint-form').submit(function() {
            var check_no = $('#add-checkpoint-no').val();
            var existing = $('#existing_checkpoints').val();
            
            existing = existing.split(','); 
            if($.inArray(check_no, existing) > -1) {
                bootbox.dialog({
                    message: 'Checkpoint No '+check_no+' already exists for this inspection, Do you still want to place it at this position and shift other by 1?',
                    title: "Confirmation box",
                    buttons: {
                        confirm: {
                            label: "Continue",
                            className: "red",
                            callback: function() {
                                $('#add-checkpoint-form')[0].submit();
                            }
                        },
                        cancel: {
                            label: "Cancel",
                            className: "blue"
                        }
                    }
                });
                
                return false;
            }
        });
    }
    
    if($('#page').length > 0 && $('#page').val() == 'realtime_dashboard') {
        setInterval(function() { realtime_dashboard(); }, 800000000);
    }
    var line_index = 1;
    $('#add-sampling-item').click(function() {
        $('#sampling-item-clone').find( ".sampling-item" ).clone().appendTo( ".items" ).addClass('sampling-item-'+line_index);
        //invoice_activate_plugins(line_index);
        line_index++;
    });
    
    $('#add-lot-range').click(function() {
        var lot_index = $('#lot-index').val();
        $('#lot-item-clone').find( ".lot-item" ).clone().appendTo( ".items" ).addClass('lot-item-'+lot_index);
        
        $('.lot-item-'+lot_index).find('.lower-val-input').attr('name', 'lower_val['+lot_index+']');
        $('.lot-item-'+lot_index).find('.higher-val-input').attr('name', 'higher_val['+lot_index+']');
        $('.lot-item-'+lot_index).find('.samples-val-input').attr('name', 'no_of_samples['+lot_index+']');
        lot_index++;
        $('#lot-index').val(lot_index);
    });
    
    $('.items').on('click', '.remove-lot-range', function() {
        $(this).closest('.lot-item').remove();
    });
    
    $('#inspection-config-sampling-type').change(function() {
        var sampling_type = $('#inspection-config-sampling-type :selected').val();
        
        $('.type-specific-div').hide();
        if(sampling_type == 'Auto') {
            $('#type-auto-div').show();
        } else if(sampling_type == 'User Defined') {
            $('#lot-size-div').show();
        } else if(sampling_type == 'Interval') {
            $('#type-interval-div').show();
            $('#lot-size-div').show();
        }
    });
    
    $('#register-inspection-product').change(function() {
        
        App.blockUI({
            target: '.register-inspection-form-portlet',
            boxed: true
        });
        
        var product = $('#register-inspection-product :selected').val();
        
        $.ajax({
            type: 'POST',
            url: base_url+'auditer/get_inspections_by_product',
            data: { product: product},
            dataType: 'json',
            success: function(resp) {
                if($('#register-inspection-inspection :selected').val() != '') {
                    $('#register-inspection-inspection').select2('val', null);
                }
                
                $('#register-inspection-inspection').html('');
                
                $('#register-inspection-inspection').append('<option value=""></option>');
                $.each(resp.inspections, function (i, item) {
                    $('#register-inspection-inspection').append($('<option>', { 
                        value: item.id,
                        text : item.name, 
                    }));
                });
                App.unblockUI('.register-inspection-form-portlet');
            }
        });
    });
    
    $('#dashboard-barcode-scan').change(function() {
        App.blockUI({
            target: '#dashboard-on-going-insp',
            boxed: true
        });
        
        var barcode = $(this).val();
        
        $.ajax({
            type: 'POST',
            url: base_url+'auditer/get_barcode_details',
            data: { barcode: barcode},
            dataType: 'json',
            success: function(resp) {
                if(resp.BUYER_SERIAL_NO) {
                    $(".dashboard-on-going-insp-table tbody tr:not('.table-title-row')").each(function() {
                        var current_sn = $(this).find('.dashboard-on-going-insp-table-serial-no').html();
                      
                        if(current_sn != resp.BUYER_SERIAL_NO) {
                            $(this).hide();
                        }
                    });
                } else {
                    $(".dashboard-on-going-insp-table tbody tr:not('.table-title-row')").show();
                }
                
                App.unblockUI('#dashboard-on-going-insp');
            }
        });
    });
    
    $('#barcode-scan').change(function() {
        
        App.blockUI({
            target: '.register-inspection-form-portlet',
            boxed: true
        });
        
        var barcode = $(this).val();
        
        $.ajax({
            type: 'POST',
            url: base_url+'auditer/get_barcode_details',
            data: { barcode: barcode},
            dataType: 'json',
            success: function(resp) {
                if(resp.MTRLID) {
                    $('#register-inspection-model-suffix').val(resp.MTRLID);
                } else {
                    $('#register-inspection-model-suffix').val('');
                }
                if(resp.BUYER_SERIAL_NO) {
                    $('#register-inspection-serial').val(resp.BUYER_SERIAL_NO);
                } else {
                    $('#register-inspection-serial').val('');
                }
                if(resp.LINE) {
                    $('#register-inspection-line').select2('val', resp.LINE);
                } else {
                    $('#register-inspection-line').select2('val', null);
                }
                if(resp.WO_NAME) {
                    $('#register-inspection-workorder').val(resp.WO_NAME);
                } else {
                    $('#register-inspection-workorder').val('');
                }
                
                App.unblockUI('.register-inspection-form-portlet');
            }
        });
    });
    
    
    var show_registration_pop = false;
    $('#register-inspection-inspection').change(function() {
        App.blockUI({
            target: '.register-inspection-form-portlet',
            boxed: true
        });
        
        $(this).closest('form').ajaxSubmit({
            url: base_url+'auditer/get_model_sampling_details',
            success:function(data) {
                data = $.parseJSON(data);
                if(data.lot_size) {
                    $('#ri-production-lot').html(data.lot_size);
                } else {
                    $('#ri-production-lot').html('');
                }
                if(data.no_of_samples) {
                    $('#ri-sampling-plan').html(data.no_of_samples);
                } else {
                    $('#ri-sampling-plan').html('');
                }
                
                if(data.completed) {
                    $('#ri-completed').html(data.completed);
                } else {
                    $('#ri-completed').html('');
                }
                
                if(data.in_progess) {
                    $('#ri-in-progress').html(data.in_progess);
                } else {
                    $('#ri-in-progress').html('');
                }
                
                if(data.lot_size) {
                    if(parseInt(data.no_of_samples) <= (parseInt(data.completed)+parseInt(data.in_progess))) {
                        show_registration_pop = true;
                    } else {
                        show_registration_pop = false;
                    }
                    
                    $('#ri-suggestion-box').show();
                } else {
                    $('#ri-suggestion-box').hide();
                }
                
                App.unblockUI('.register-inspection-form-portlet');
            }
        });
    });
    
    $('#register-inspection-form').submit(function() {
        if($(this).valid()) {
            if(show_registration_pop) {
                bootbox.dialog({
                    message: 'Sampling plan for this Model.Suffix is already completed, Do you still want to continue?',
                    title: "Confirmation box",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: "red",
                            callback: function() {
                                
                                $('#register-inspection-form')[0].submit();
                            }
                        },
                        cancel: {
                            label: "No",
                            className: "blue"
                        }
                    }
                });
                
                return false;
            }
        }
    });
    
    $('#register-inspection-submit').click(function() {
        $('#register-inspection-remark').removeClass('required');
        
        var elem = $('#register-inspection-submit');
        var lsl = $('#register-inspection-checkpoint-lsl').val();

        var usl = $('#register-inspection-checkpoint-usl').val();
        var val = $('#audit_value').val();
        
        if(val == '') {
            elem.closest('form').submit();
        }
        
        if(lsl == '' && usl == '') {
            elem.closest('form').submit();
        }
        
        //alert(parseFloat(val)+' '+parseFloat(lsl)+' '+parseFloat(usl));
        var exploded_value = val.split(",");
        var error = false;

        for(var i = 0; i < exploded_value.length; i++) {
            if(lsl != '' && parseFloat(exploded_value[i]) < parseFloat(lsl)) {
                error = true;
                break;
            }
            
            if(usl != '' && parseFloat(exploded_value[i]) > parseFloat(usl)) {
                error = true;
                break;
            }
        }
        
        if(error) {
            bootbox.dialog({
                message: 'Are you sure you want to mark this checkpoint as NG?',
                title: "Confirmation box",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "red",
                        callback: function() {
                            $('#register-inspection-remark').addClass('required');
                            elem.closest('form').submit();
                        }
                    },
                    cancel: {
                        label: "No",
                        className: "blue"
                    }
                }
            });
        } else {
            elem.closest('form').submit();
        }
        
        return false;
    });
    
    $('.check-judgement-button').click(function() {
        var target = $(this).attr('href');
        var audit_id = $(this).attr('data-id');
        
        var current_row = $(this).closest('tr');
        
        current_row.find('.judgement-col .loading').show();
        
        $.ajax({
            type: 'POST',
            url: target,
            data: { audit_id: audit_id},
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    current_row.find('.judgement-col').html(resp.judgement);
                }
                
                current_row.next('tr').find('.check-judgement-button').trigger('click');
            }
        });
        
        return false;
    });
    
    $('#tool-wise-model-sel').change(function() {
        
        App.blockUI({
            target: '.portlet',
            boxed: true
        });
        
        var tool = $('#tool-wise-model-sel :selected').val();
        
        $.ajax({
            type: 'POST',
            url: base_url+'products/get_models_by_tool',
            data: { tool: tool},
            dataType: 'json',
            success: function(resp) {
                if($('#model-sel-by-tool :selected').val() != '' && $('#model-sel-by-tool :selected').val() != 'All') {
                    $('#model-sel-by-tool').select2('val', null);
                }
                
                $('#model-sel-by-tool').html('');
                
                $('#model-sel-by-tool').append('<option value="All">All</option>');
                $.each(resp.models, function (i, item) {
                    $('#model-sel-by-tool').append($('<option>', { 
                        value: item.model,
                        text : item.model, 
                    }));
                });
                App.unblockUI('.portlet');
            }
        });
    });

    $('#exclude-form-insp-sel').change(function() {
        
        App.blockUI({
            target: '.excluded_checkpoint-add-form-portlet',
            boxed: true
        });
        
        var insp        = $('#exclude-form-insp-sel :selected').val();
        var existing = 0;
        if($('#existing-id').length > 0) {
            var existing    = $('#existing-id').val();
        }
        
        $.ajax({
            type: 'POST',
            url: base_url+'inspections/get_inspection_checkpoints',
            data: { inspection_id: insp, existing: existing},
            success: function(resp) {
                $('#exclude-checkpoints-section').html(resp);
                
                App.unblockUI('.excluded_checkpoint-add-form-portlet');
            }
        });
    });

    $('#ref-link-checkpoint-config-insp-sel').change(function() {
        
        App.blockUI({
            target: '.checkpoint_config-add-form-portlet',
            boxed: true
        });
        
        var insp        = $('#ref-link-checkpoint-config-insp-sel :selected').val();
        var existing = 0;
        if($('#existing-id').length > 0) {
            var existing    = $('#existing-id').val();
        }
        
        $.ajax({
            type: 'POST',
            url: base_url+'references/get_inspection_checkpoints',
            data: { inspection_id: insp, existing: existing},
            success: function(resp) {
                $('#ref-link-checkpoints-section').html(resp);
                
                App.unblockUI('.checkpoint_config-add-form-portlet');
            }
        });
    });
    
    $('.page-content-wrapper').on('submit', '.lot-id-form', function() {
        $modal_content = $(this).closest('.modal-content');
        
        if($modal_content.find('#oqc_lot_id_field').val() == '') {
            $modal_content.find('.alert-danger').removeClass('display-hide');
            
            return false;
        }
    });
    
    $('.page-content-wrapper').on('submit', '.attach-guideline-form', function() {
        $target = $(this).attr('action');
        $parts = $target.split('/');
        $checkpoint_id = $parts[$parts.length-1];
        $checkpoint_row = $('.checkpoint-'+$checkpoint_id);
        $modal_content = $(this).closest('.modal-content');
        
        if($modal_content.find('.guideline-image-field').val() == '') {
            $modal_content.find('.alert-danger').removeClass('display-hide');
        } else {
            $checkpoint_row.find('.guideline-image-loading').show();
            $checkpoint_row.find('.guideline-image-href').hide();
            $(this).ajaxSubmit({
                success:function(data) {
                    data = $.parseJSON(data);
                    if(data.status == 'success') {
                        $checkpoint_row.find('.guideline-image-href').attr('href', base_url+data.file);
                        $checkpoint_row.find('.guideline-image-href').show();
                        
                    } else {
                        alert('Something went wrong, Please try again.');
                    }
                    $checkpoint_row.find('.guideline-image-loading').hide();
                    $modal_content.find('.attach-guideline-modal-close').trigger('click');
                }
            }); 
        }
        
        return false;
    });
    
    $('.page-content-wrapper').on('submit', '.adjust-production-form', function() {
        $target = $(this).attr('action');
        $parts = $target.split('/');
        $row_id = $parts[$parts.length-1];
        $row = $('.producton-plan-'+$row_id);
        $modal_content = $(this).closest('.modal-content');
        
        if($modal_content.find('input').val() == '') {
            $modal_content.find('.alert-danger').removeClass('display-hide');
        } else {
            $row.find('.ajay-image-loading').show();
            $row.find('.return-content-section').hide();
            $(this).ajaxSubmit({
                success:function(data) {
                    data = $.parseJSON(data);

                    if(data.status == 'success') {
                        $row.find('.return-content-section').html(data.html);
                        $row.find('.return-content-section').show();
                        
                    } else {
                        alert('Something went wrong, Please try again.');
                    }
                    
                    $row.find('.ajay-image-loading').hide();
                    $modal_content.find('.adjust-production-modal-close').trigger('click');
                }
            }); 
        }
        
        return false;
    });
    
    $('.page-content-wrapper').on('submit', '.adjust-sampling-form', function() {
        var btn = $(document.activeElement).val();
        if(btn == 'skip') {
            $(this).append('<input type="hidden" name="skip" value="skip" />');
        }
        $target = $(this).attr('action');
        $parts = $target.split('/');
        $row_id = $parts[$parts.length-1];
        $row = $('.sampling-plan-'+$row_id);
        $modal_content = $(this).closest('.modal-content');
        
        if(btn != 'skip' && $modal_content.find('input').val() == '') {
            $modal_content.find('.alert-danger').removeClass('display-hide');
        } else {
            $(this).ajaxSubmit({
                success:function(data) {
                    data = $.parseJSON(data);

                    if(data.status == 'success') {
                        $row.closest('td').html(data.html);
                    } else {
                        alert('Something went wrong, Please try again.');
                    }
                    
                    $modal_content.find('.adjust-sampling-modal-close').trigger('click');
                }
            }); 
        }
        
        return false;
    });
    
    $('.page-content-wrapper').on('submit', '.automate-settings-form', function() {
        $target = $(this).attr('action');
        $parts = $target.split('/');
        $checkpoint_id = $parts[$parts.length-1];
        $checkpoint_row = $('.checkpoint-'+$checkpoint_id);
        $modal_content = $(this).closest('.modal-content');
        
        if($modal_content.find('#automate_result_row').val() == '' || $modal_content.find('#automate_result_col').val() == '') {
            $modal_content.find('.alert-danger').removeClass('display-hide');
        } else {
            $checkpoint_row.find('.automate-setting-loading').show();
            $(this).ajaxSubmit({
                success:function(data) {
                    data = $.parseJSON(data);
                    if(data.status == 'success') {
                        $checkpoint_row.find('.automate-setting-text').html(data.col+data.row);
                    } else {
                        alert('Something went wrong, Please try again.');
                    }
                    $checkpoint_row.find('.automate-setting-loading').hide();
                    $modal_content.find('.automate-setting-modal-close').trigger('click');
                }
            }); 
        }
        
        return false;
    });

    if($('.dashboard-noti').length > 0) {
        $('.dashboard-noti').pulsate({
            color: "#fdbe41",
            reach: 50,
            speed: 1000,
            glow: true
        });
    }
    
    $('.easy-pie-chart .number.transactions').easyPieChart({
        animate: 1000,
        size: 75,
        lineWidth: 3,
        barColor: App.getBrandColor('yellow')
    });
    
    $('#na-confirm').click(function() {
        
        var elem = $(this);
        bootbox.dialog({
            message: 'Are you sure you want to mark this checkpoint as NA?',
            title: "Confirmation box",
            buttons: {
                confirm: {
                    label: "Yes",
                    className: "red",
                    callback: function() {
                        if($('#audit_value').length > 0) {
                            $('#audit_value').removeClass('required');
                        }
                        
                        $('#na-button').trigger('click');
                    }
                },
                cancel: {
                    label: "No",
                    className: "blue"
                }
            }
        });
    });
    
    $('#ng-confirm').click(function() {
        $('#register-inspection-remark').removeClass('required');
        
        var elem = $(this);
        bootbox.dialog({
            message: 'Are you sure you want to mark this checkpoint as NG?',
            title: "Confirmation box",
            buttons: {
                confirm: {
                    label: "Yes",
                    className: "red",
                    callback: function() {
                        $('#register-inspection-remark').addClass('required');
                        $('#ng-button').trigger('click');
                    }
                },
                cancel: {
                    label: "No",
                    className: "blue"
                }
            }
        });
    });
    
    // Example #3
    if($('#tool-master').length > 0) {
        var tools = new Bloodhound({
          datumTokenizer: function(d) { return d.tool; },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: base_url+'products/get_all_tools'
        });
        tools.initialize();
        $('#tool-master').typeahead(null, {
            displayKey: 'tool',
            hint: (App.isRTL() ? false : true),
            source: tools.ttAdapter()
        });
    }
    
    if($('#model-master').length > 0) {
        var models = new Bloodhound({
          datumTokenizer: function(d) { d.model; },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: base_url+'auditer/get_model_suggestions'
        });
        models.initialize();
        $('#model-master').typeahead(null, {
            displayKey: 'model',
            hint: (App.isRTL() ? false : true),
            source: models.ttAdapter()
        });
    }
    
    if($('#inspection-full-auto').length > 0) {
        $('#inspection-full-auto').change(function() {
            $is_checked = $('#inspection-full-auto:checked').val();
            if($is_checked == 1) {
                $('.non-full-auto').hide();
                $('.full-auto-section').show();
                $case = $('#automate-special-case :selected').val();
                if($case === 'With Checkpoints') {
                    $('.automate-settings').show();
                }
            } else {
                $('.non-full-auto').show();
                $('.full-auto-section').hide();
                $('.automate-settings').hide();
            }
        });
    }
    
    if($('#inspection-edit').length > 0) {
        $insp_type = $('#add-inspection-insp-type :selected').val();

        if($insp_type == 'regular') {
            $is_checked = $('#inspection-full-auto:checked').val();
            if($is_checked == 1) {
                $('.non-full-auto').hide();
                $('.full-auto-section').show();
                $case = $('#automate-special-case :selected').val();
                if($case === 'With Checkpoints') {
                    $('.automate-settings').show();
                }
            } else {
                $('.non-full-auto').show();
                $('.full-auto-section').hide();
            }
        } else if($insp_type == 'interval') {
            $is_checked = $('#interval-inspection-attach-report:checked').val();
            if($is_checked == 1) {
                $('.checkpoint-upload-section').hide();
                $('.automate-checkboxes').hide();
                $('.attach-report-div').show();
            } else {
                $('.checkpoint-upload-section').show();
                $('.automate-checkboxes').hide();
                $('.attach-report-div').hide();
            }
        }
    }

    if($('#interval-inspection-attach-report').length > 0) {
        $('#interval-inspection-attach-report').change(function() {
            $is_checked = $('#interval-inspection-attach-report:checked').val();
            if($is_checked == 1) {
                $('.checkpoint-upload-section').hide();
                $('.automate-checkboxes').hide();
                $('.attach-report-div').show();
            } else {
                $('.checkpoint-upload-section').show();
                $('.automate-checkboxes').hide();
                $('.attach-report-div').hide();
            }
        });
    }
    
    if($('#add-inspection-insp-type').length > 0) {
        $('#add-inspection-insp-type').change(function() {
            $insp_type = $('#add-inspection-insp-type :selected').val();
            if($insp_type == 'regular') {
                $('.automate-checkboxes').show();
                if($('#inspection-full-auto:checked').val()) {
                    $('.non-full-auto').hide();
                    $('.full-auto-section').show();
                    $case = $('#automate-special-case :selected').val();
                    if($case === 'With Checkpoints') {
                        $('.automate-settings').show();
                    }
                }
                $('.interval-type').hide();
            } else {
                $('.non-full-auto').show();
                $('.interval-type').show();
                $('.automate-checkboxes').hide();
                
                $('.full-auto-section').hide();
                $('.automate-settings').hide();
            }
        });
    }
    
    if($('.inspection-type').length > 0) {
        $('.inspection-type').change(function() {
            $type = $('.inspection-type:checked').val();
            if($type === 'Model.Suffix') {
                $('.config-model-sel-div').show();
                $('.config-tool-sel-div').hide();
            } else {
                $('.config-model-sel-div').hide();
                $('.config-tool-sel-div').show();
            }
        });
    }
    
    if($('.reference-sel-type').length > 0) {
        $('.reference-sel-type').change(function() {
            $type = $('.reference-sel-type:checked').val();
            if($type === 'File') {
                $('.add-reference-file-sel-div').show();
                $('.add-reference-url-sel-div').hide();
            } else {
                $('.add-reference-file-sel-div').hide();
                $('.add-reference-url-sel-div').show();
            }
        });
    }
    
    if($('#automate-special-case').length > 0) {
        $('#automate-special-case').change(function() {
            $case = $('#automate-special-case :selected').val();
            if($case === 'With Checkpoints') {
                $('.automate-settings').show();
            } else {
                $('.automate-settings').hide();
            }
        });
    }
    
    if($('#delete-multiple').length > 0) {
        $('#delete-multiple').click(function(){
            var form = $('#delete-multiple').closest('form');
            
            if(form.find('.checkboxes:checked').length == 0) {
                
                bootbox.dialog({
                    message: 'Please select the rows you want to deleted.',
                    title: 'Alert',
                    buttons: {
                        confirm: {
                            label: "OK",
                            className: "button"
                        }
                    }
                });
                
                return false;
            }
            
            bootbox.dialog({
                message: 'Are you sure you want to delete all these records?',
                title: "Confirmation box",
                buttons: {
                    confirm: {
                        label: "Yes",
                        className: "button",
                        callback: function() {
                            form.submit();
                        }
                    },
                    cancel: {
                        label: "No",
                        className: "button white"
                    }
                }
            });
        });
    }
    
    $('#update-inspection-config-inspection').change(function() {
        App.blockUI({
            target: '.portlet',
            boxed: true
        });
        
        var insp = $('#update-inspection-config-inspection :selected').val();
        
        $.ajax({
            type: 'POST',
            url: base_url+'inspections/get_inspection_ajax',
            data: { inspection_id: insp},
            dataType: 'json',
            success: function(resp) {
                if(resp.insp_type == 'interval') {
                    $('#inspection-config-sampling-type').select2('val', 'Interval');
                    //$('#inspection-config-sampling-type').attr('readonly', 'true');
                } else {
                    if($('#inspection-config-sampling-type :selected').val()) {
                        $('#inspection-config-sampling-type').select2('val', null);
                    }
                    
                    //$('#inspection-config-sampling-type').removeAttr('readonly');
                }
                App.unblockUI('.portlet');
            }
        });
        
    });
    
    $('#dashboard-date').on('changeDate', function() {
        // if($('#first-time').val() == 1) {
            // $('#first-time').val(0);
            // return false;
        // }
        
        window.location.href = base_url+'dashboard/set_dashboard_date/'+$(this).find('input').val();
    });
    
    $('#submit-checklist').click(function(){
        if($('.liChild:checked').length == $('.liChild').length) {
            window.location.href = base_url+'auditer/checklist?status=done';
        } else {
            bootbox.dialog({
                message: 'Please complete all the check items before submit.',
                title: 'Alert',
                buttons: {
                    confirm: {
                        label: "OK",
                        className: "button"
                    }
                }
            });
        }
    });
    
});

function realtime_dashboard() {
    var base_url = $('#base_url').val();
    
    App.blockUI({
        target: '.portlet-body',
        boxed: true
    });
    
    $.ajax({
        type: 'POST',
        url: base_url+'dashboard/realtime',
        success: function(resp) {
            $('.realtime-dashboard-portlet').find('.portlet-body').html(resp);
            App.unblockUI('.portlet-body');
        }
    });
}

function mandatory_popup($links) {
    return true;
    bootbox.dialog({
        message: 'For this inspection <span style="color:#c80541;">'+$links+'</span> links are mandatory. You need to open these links else you wont be able to finish the inspection.',
        title: "Mandatory Box",
        buttons: {
            confirm: {
                label: "OK",
                className: "button"
            }
        }
    });
	
	
}

function show_page(page_no) {
    $('#page-no').val(page_no);
    
    $('#page-no').closest('form').submit();
}

function get_paired_insp(audit_id) {
    var base_url = $('#base_url').val();
    
    App.blockUI({
        target: '.inspection-detail-sidebar',
        boxed: true
    });
    
    $.ajax({
        type: 'POST',
        url: base_url+'auditer/get_paired_audit_details',
        data: { audit_id: audit_id},
        dataType: 'json',
        success: function(resp) {
            $.each(resp, function(i, item) {
                $('.paired-section-template').find('.paired-model-suffix').html(item.model_suffix);
                $('.paired-section-template').find('.paired-serial-no').html(item.serial_no);
                
                $('.paired-section').append($('.paired-section-template').html());
            });

            App.unblockUI('.inspection-detail-sidebar');
        }
    });
}