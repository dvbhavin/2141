jQuery(document).ready(function() {
    
    // Start Datatable
    var table = $('#ctm_dt, .ctm_dt');
    table.DataTable({
        responsive: true,

        // DOM Layout settings
        dom: `<'row'<'col-sm-12'tr>>
        <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

        lengthMenu: [5, 10, 25, 50],

        pageLength: 10,

        language: {
            'lengthMenu': 'Display _MENU_',
        },

        'columnDefs' : [
            { 'visible': false, 'targets': [0] }
        ],

        // Order settings
        order: [[0, 'desc']],
    });
    // End Datatable

    // Start Select2
    $('.ctm_s2').select2({
        placeholder: 'Choose ..',
        allowClear: true
    });

    /*var data = [{
            id: 0,
            text: 'Enhancement'
        }, {
            id: 1,
            text: 'Bug'
        }, {
            id: 2,
            text: 'Duplicate'
        }, {
            id: 3,
            text: 'Invalid'
        }, {
            id: 4,
            text: 'Wontfix'
        }];

    $('.ctm_s2_ar').select2({
        placeholder: 'Choose ..',
        allowClear: true,
        data: data
    });*/
    // End Select2

    // Start CK Editor
    $('.ctm_sn').summernote({
        height: 400,
        tabsize: 2,
        followingToolbar: true,
    });
    // End CK Editor

    // range picker
    $('.ctm_datepicker').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        templates: arrows,
        format: 'dd/mm/yyyy'
    });

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }
    
});