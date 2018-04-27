<?php
	$mysqli = new mysqli("localhost", "root", "", "konva") or die ("Could not connect mysql server");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="../js/konva.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script>
$(document).ready(function (e){
	$("#uploadForm").on('submit',(function(e){
	e.preventDefault();
		$.ajax({
		url: "upload_file.php",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){			
		$("#image_preview").html(data);	/*
		$("#image_preview").load(data);*/
		},
		error: function(){} 	        
		});
	}));
	/*$(document).on('click', '.itemimage', function(){
	    alert("success");
	});*/
});

 
</script>
</head>
<body style="background: #ebe9e2">
<style>
/*right click*/
  .context-menu ul{ 
    z-index: 1000;
    position: absolute;
    overflow: hidden;
    border: 1px solid #CCC;
    white-space: nowrap;
    font-family: sans-serif;
    background: #FFF;
    color: #333;
    border-radius: 5px;
    padding: 0;
}
/* Each of the items in the list */
.context-menu ul li {
    padding: 8px 12px;
    cursor: pointer;
    list-style-type: none;
}
.context-menu ul li:hover {
    background-color: #DEF;
}
 </style>
<div class="container">
  <h1>Story Creator</h1>
  <p>As you can see, there are two different sections appear below. Kindly select Background image with the defiend dimension for the better preview. Similarly, select the objects images from the local library and select it for the preview.</p>      
  <p>Drag the object as you want and select the object and click on delete if you want to delete the object.</p>      
  <div class="row">
    <div class="col-sm-3" style="background-color:grey;">
     <!--Background image display starts here-->
      <h2>Background</h2> 
    <span class="back"><img class="img-responsive img-thumbnail" src="../images/Background4.png" width="50px"></span>
     <span class="back"><img class="img-responsive img-thumbnail" src="../images/Background5.jpg" width="50px" ></span>
     
     <!--Object images display starts here-->
     <br>
     <h2>Images</h2>
     
    <div id="image_preview">
     	<?php 
    $query = $mysqli->query("select * from images where 1");
    while($data=$query->fetch_array())
    {
    	$rows[] = $data;		
	}

	foreach($rows as $data)
	{
		?>		
		<span class="itemimage"><img class="img-responsive img-thumbnail" src="/images/<?php echo $data['name']; ?>" width="50px" height="50px"></span>
		<?php
	}
    ?>
    </div>
       
       <br>
       
       <!--Form to upload Object Images-->
     <form action="upload_file.php" id="uploadForm" method="post" enctype="multipart/form-data">
	  <input type="file" id="upload_file" name="files"  multiple/>
	  <!--<input type="file" id="upload_file" name="upload_file[]"  multiple/>-->
	  <input type="submit" name='submit_image' value="Upload Image"/>
	 </form>
     <br>
     
     <!--Text Field Starts here-->
      <h2>Text</h2> 
      <div class="form-group">
        <label for="exampleInputFile">Texto</label>
        <input type="text" class="addtextt">
        <br>
        <span class="glyphicon glyphicon-plus" aria-hidden="true"> Add another Text</span>

      </div>

	<br>
	
	<!--RIght Context Menu Example-->
	<div class="context-menu" id="context-menu" style="display:none;position:absolute;z-index:99">
    <ul>
      <li><a href="#" id="up"><i class="fa fa-arrow-up"></i> Move Up</a></li>       
      <li><a href="#" id="down"><i class="fa fa-arrow-down"></i> Move Down</a></li>       
      <li><a href="#" id="deleteButton"><i class="fa fa-trash"></i> Delete</a></li>              
      <li><a href="#" id="close"><i class="fa fa-times"></i> Close</a></li>       
    </ul>
</div>
       
    <!--Layer Management Buttons-->
    <!--<button id="up">Move Up</button>
    <button id="down">Move Down</button>
    <button id="deleteButton">Delete</button>-->
    <br>
    </div>
    <div class="col-sm-9" style="background-color:green;">
      <div id="canvascontent"  oncontextmenu ="event.preventDefault();$('#context-menu').show();$('#context-menu').offset({'top':mouseY,'left':mouseX})"></div>
    </div>
  </div>
</div>
    
    
    
    <!--Script for the Konva Logic Starts here-->
    
  <script>
    var width = window.innerWidth;
    var height = window.innerHeight;
    var objectList = [];
    
    var stage = new Konva.Stage({
      container: 'canvascontent',
      width: width,
      height: height
    });

    var layer = new Konva.Layer();
     // add the layer to the stage
      stage.add(layer);
    var Imgitem;
    //Background Image Object  
 	$(document).on('click', '.back', function(){
    imgback = $(this).find('img').attr('src');


    var imageObj = new Image();
    imageObj.onload = function() {
    	// remove previous backgorun
 			layer.find('.backgroud').destroy();
      
      var background = new Konva.Image({
      	name: 'backgroud',
        image: imageObj,
        width: 872,
        height: 662,
      });
      // add new one
      layer.add(background);
      background.moveToBottom();
      layer.draw();

     
    };
    imageObj.src = imgback ; 
    
});

	// Image Objects
	$(document).on('click', '.itemimage', function(){
    Imgitem = $(this).find('img').attr('src');

  	var width = window.innerWidth;
    var height = window.innerHeight;

    function drawImage(imageObj) {
    
        var Imgitem = new Konva.Image({
            image: imageObj,
            width: 80,
            height: 80,
            draggable: true,
            name:'trans'
        });

        // add cursor styling
        Imgitem.on('mouseover touchstart', function() {
            document.body.style.cursor = 'pointer';
        });
        Imgitem.on('mouseout touchend', function() {
            document.body.style.cursor = 'default';
        });

        layer.add(Imgitem);
        layer.draw();
    }
    var imageObj = new Image();
    imageObj.onload = function() {
        drawImage(this);
    };
    imageObj.src = Imgitem;
    
});

	
	//Transformer Object Management
	layer.on('dblclick', function(evt) {
		var id = evt.target.id();
		console.log("Fired Event ID is :"+ id);
		if(id == 'editor')
		{
			// create textarea over canvas with absolute position

            // first we need to find its positon
            var textPosition = evt.target.getAbsolutePosition();
            var stageBox = stage.getContainer().getBoundingClientRect();

            var areaPosition = {
                x: textPosition.x + stageBox.left,
                y: textPosition.y + stageBox.top
            };


            // create textarea and style it
            var textarea = document.createElement('textarea');
            document.body.appendChild(textarea);

            textarea.value = evt.target.text();
            textarea.style.position = 'absolute';
            textarea.style.top = areaPosition.y + 'px';
            textarea.style.left = areaPosition.x + 'px';
            textarea.style.width = this.width();

            textarea.focus();


            textarea.addEventListener('keydown', function (e) {
                // hide on enter
                if (e.keyCode === 13) {
                    evt.target.text(textarea.value);
                    layer.draw();
                    document.body.removeChild(textarea);
                }
            });
		}
		else
		{
			//Remove Transformer		
        	stage.find('Transformer').destroy(); 
		//Remove Previous Object selection
		var shape = stage.find('.deleteObject');
        	shape.stroke('none');
	        shape.strokeWidth(0);
	        shape.name('trans');	
        var shape = evt.target;
        if (!evt.target.hasName('backgroud')) {             	
		        shape.stroke('lime');
		        shape.strokeWidth(10);
		        shape.name('deleteObject');
        	}
	    layer.draw();
		}        
    });
	
	//Text Object
	$('.addtextt').change(function(){
    	texttt = $(this).val();
	    var textNode = new Konva.Text({            
		    x: 50,
		    y: 50,
		    text: texttt,
		    fontSize: 30,
		    fontFamily: 'Calibri',
	  		fill: 'black',
            draggable: true,
            name: 'trans',
            id: 'editor',
        });
    	

        layer.add(textNode);
        layer.draw();

    // to align text in the middle of the screen, we can set the
    // shape offset to the center of the text shape after instantiating it
    

});

	
    /*Transformation Logic Start here*/    
    stage.on('click', function (e) {
      // if click on empty area - remove all transformers
      if (e.target === stage) {
      	 	console.log('click on shape', e.target);
        	var shape = stage.find('.deleteObject');
        	shape.stroke();
	        shape.strokeWidth(0);
	        shape.name('trans');	        
        	stage.find('Transformer').destroy();        	      	
        	layer.draw();
        return;
      }
      else
      {
      	 console.log('click on shape', e.target);
      	 	
      }
      // do nothing if clicked NOT on our rectangles
      if (!e.target.hasName('trans')) {
        return;
      }
      // remove old transformers
      // TODO: we can skip it if current rect is already selected
      stage.find('Transformer').destroy();

      // create new transformer
      var tr = new Konva.Transformer();
      layer.add(tr);
      tr.attachTo(e.target);
      layer.draw();
    })
    
    
    /*Object Delete Function*/
	$('#up').click(function(){	
			var shape = stage.find('.deleteObject');		        
        	shape.moveUp();     	      	
        	layer.draw();
	})
    
    
    /*Object Layer Moving UP Function*/
	$('#down').click(function(){ 
        	var shape = stage.find('.deleteObject');   
	        	shape.moveDown();     	      	
	        	layer.draw();   
	})
    
    
    /*Object Layer Moving DOWN Function*/
	$('#deleteButton').click(function(){			        
        	stage.find('.deleteObject').destroy();
        	stage.find('Transformer').destroy();
        	$(".context-menu").hide(100);      	         	      	
        	layer.draw();
	})
	
	
    
	// save stage as a json string
    var json = stage.toJSON();

    console.log(json);
    
    
    /*Right Click Script*/ 
    
     	var mouseX;
	    var mouseY;
	    $(document).mousemove(function(e) {
	       mouseX = e.pageX; 
	       mouseY = e.pageY;
	    });  
    
	    //Script to bind Menu to the stag//
	    $(document).bind("mousedown", function (e) {
	    // If the clicked element is not the menu
	    if (!$(e.target).parents(".context-menu").length > 0) {    
	        // Hide it
	        $(".context-menu").hide(100);
	    }
	  });
  
	  //Script to close the MenuContext Window//
	  $('#close').click(function(){			        
	        $(".context-menu").hide(100);
		})
    
    
    
    
  </script> 
    
</body>
</html>