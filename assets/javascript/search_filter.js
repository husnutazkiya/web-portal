$(document).ready(function(){
    const kode = $("#kode");
    const filter_search = $("#filter-search");
    const freetext_value = $("#txt-filter-search");
    const fixedtext_value = $("#filter-select-option");
    const btn_filter = $("#filter-button");
    const btn_reset = $("#reset-button");
    
    filter_search.on("change", function(){
        const __this = $(this);
        const this_value = __this.val().split("#");
        const filter_type = this_value[2];
        const filter_value = this_value[1];

        if(filter_type == "fixed_text"){
            freetext_value.addClass("hidden");
            fixedtext_value.removeClass("hidden");

            $.ajax({
                url: base_url+'getoption',
                type: 'get',
                data: {
                    value: filter_value,
                    kode: kode.val()
                },
                success: function(response){
                    const obj = JSON.parse(response)[0]
                    fixedtext_value.html(obj)

                }
            })
        }else if(filter_type == "free_text"){
            freetext_value.removeClass("hidden");
            fixedtext_value.addClass("hidden");
        }
    });

    const CallBugList = function CallBugList(query){
        $.ajax({
            url: base_url+'bug/filter-query',
            type: 'get',
            data: {
                kode : kode.val(),
                query : query
            },
            success: function(response){
                $(".dt-grid").html(response)
            }
        })
    }
    btn_filter.on("click", function(){
        const search = filter_search.val().split("#");
        const filter_type = search[2];
        const filter_value = search[1];

        let query = "";
        if(filter_type != "" && filter_value != ""){
            if(fixedtext_value.hasClass("hidden") == false){
                $: query = filter_type+"#"+filter_value+"#"+fixedtext_value.val();
            }

            if(freetext_value.hasClass("hidden") == false){
                $: query = filter_type+"#"+filter_value+"#"+freetext_value.val();
            }

            CallBugList(query);
        }
    });

    btn_reset.on("click" , function(){
        $("#filter-search").val("1##").trigger("change")

        $("#filter-select-option").val(0).trigger("change");
        $("#filter-select-option option").remove();
        $("#filter-select-option").append("<option value='0'>Select</option>");

        CallBugList("1##");
    })
})