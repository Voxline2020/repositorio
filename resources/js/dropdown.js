$("#company").change(function(event){
		$.get("store2/"+event.target.value+"",function(response,company){
				$("#store").empty();
				for(i=0; i<response.length; i++){
					$("#store").append("<option value='"+ response[i].id+"'> "+response[i].name+"</option>");

				}
		});
});

