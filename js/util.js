function parseResponse2DataForm(response)
{
	var json_obj = JSON.parse(response);
	var response_str = '';
	var cnt = 0;
	for(var key in json_obj)
	{
		if(key=='name' || key=='size')
			continue;
		cnt++;
		response_str += '<label for="attributename'+cnt+'">Attribute Name:</label></br>' + 
		  		   '<input id="attributename'+cnt+'" name="attributename'+cnt+'" type="text" size="30" value="'+key+'"/></br>'+
		  		   '<label for="attributevalue'+cnt+'">Attribute Value:</label></br>'+
		  		   '<input id="attributevalue'+cnt+'" name="attributevalue'+cnt+'" type="text" size="30" value="'+json_obj[key]+'"/></br>';
	}
	response_str += '<input id="optionalattributenum" name="optionalattributenum" style="display:none;" value="'+cnt+'"/>';
	return response_str;
}