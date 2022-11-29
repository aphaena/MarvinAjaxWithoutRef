
	  
			 
			  function hightlighttexte(idorclass){
				 // alert (mysearch);
				  myString = mysearch;
				  $(idorclass).highlight(myString);
				  /*
				  myString= myString.replace(" de "," ");
				  myString= myString.replace(" à "," ");
				  myString= myString.replace(" l "," ");
				  myString= myString.replace(" d "," ");
				  myString= myString.replace(" la "," ");
				  myString= myString.replace(" La "," ");
				  myString= myString.replace(" le "," ");
				  myString= myString.replace(" Le "," ");
				  myString= myString.replace(" a "," ");
				  myString= myString.replace(" des "," ");
				  myString= myString.replace(" ce "," ");
				  myString= myString.replace(" Ce "," ");
				  myString= myString.replace(" cet "," ");
				  myString= myString.replace(" Cet "," ");
				  myString= myString.replace(" avec "," ");
				  myString= myString.replace(" qui "," ");
				  myString= myString.replace(" que "," ");
				  myString= myString.replace(" ou "," ");
				  myString= myString.replace(" une "," ");
				  myString= myString.replace(" Une "," ");
				  myString= myString.replace(" in "," ");
				  myString= myString.replace(" In "," ");
				  myString= myString.replace(" of "," ");
				  myString= myString.replace(" Of "," ");
				  myString= myString.replace(" the "," ");
				  myString= myString.replace(" The "," ");
				  myString= myString.replace(" an "," ");
				  myString= myString.replace(" An "," ");
				  myString= myString.replace(" Or "," ");
				  myString= myString.replace(" as "," ");
				  myString= myString.replace(" As "," ");
				  // alert(myString);
				  */
				  myArray = myString.split(" "); 
				  for(i=0;i<myArray.length;i++) 
				  { 
				   $(idorclass).highlight(myArray[i], true, "hls");	
				 
				  }
				  				  				 			  
			  }



 jQuery.fn.extend({
    highlight: function(search, insensitive, hls_class){
      var regex = new RegExp("(<[^>]*>)|(\\b"+ search.replace(/([-.*+?^${}()|[\]\/\\])/g,"\\$1") +")", insensitive ? "ig" : "g");
      return this.html(this.html().replace(regex, function(a, b, c){
        return (a.charAt(0) == "<") ? a : "<strong class=\""+ hls_class +"\">" + c + "</strong>";
      }));
    }
  });
  jQuery(document).ready(function($){
    if(typeof(hls_query) != 'undefined'){
      $("#post-area").highlight(hls_query, 1, "hls");
    }
  });// JavaScript Document