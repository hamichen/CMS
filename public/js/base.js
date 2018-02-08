$(function(){
    $(".btn-anchor").click(function(){
        var location = window.location.href;
        $.post('/user/set/add-anchor-resource',{uri:location},function(res){
            if(res.success){
                window.location.reload();
            }
        })
    });
    $(".btn-remove-anchor").click(function(){
        var location = window.location.href;
        $.post('/user/set/remove-anchor-resource',{uri:location},function(res){
            if(res.success){
                window.location.reload();
            }
        })
    });

});
