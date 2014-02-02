	function update_clk(){
			
			var date = new Date();
			
			/* get date */
			var year = date.getFullYear();
			var month = date.getMonth()+1; /* add one for correct format */
			var day = date.getDate();
			
			/* get time */
			var hours = date.getHours();
			var minutes = date.getMinutes();
			
			/* combine variables to a string */
			var string = day + "/" + month + "-" + year + ". " + hours + ":" + minutes;
			
			/* change value of string */
			document.getElementById("clock").firstChild.nodeValue = string;
		}