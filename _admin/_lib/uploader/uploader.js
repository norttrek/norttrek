var Uploader = function(el){ 
  var root = this;
  var dropArea = el;
  var list = [];
  var post_var;
  var totalSize = 0; 
  var totalProgress = 0;
  var path_dir = "/uploads/";
  var path_php = "/juanjo/_lib/uploader/upload.php";
  var canvas;
  var context;
  var el;
  var count = document.getElementById('count');

  function init(){ 
    this.el = el;
    root.addEventListeners();
	if(!$("#uploader_overlay").length){
	  $('body').prepend('<div id="uploader_overlay" style="width:100%; height:100%; background:url(_lib/uploader/bck.png); position:fixed; display:none; z-index:999999999999999;"><canvas id="cv_progress" width="500" height="20" style="margin-top:40%;"></canvas></div>'); 
	  
	}
	canvas = document.querySelector('canvas');
      context = canvas.getContext('2d');
    
  }
  
  
  this.set_post_var = function(id){ this.post_var = id; }
  
  this.addEventListeners = function(){
    dropArea.addEventListener('drop', root.drop_handler, false);
    dropArea.addEventListener('dragover', root.dragover_handler, false);
	window.addEventListener("dragover",function(e){ e = e || event; e.preventDefault(); },false);
	window.addEventListener("drop",function(e){ e = e || event; e.preventDefault(); },false);
	$('.onClickRemoveThis').live('click',function(e){ root.remove_uploaded_file(e); });
	
	
  }
  
  this.remove_uploaded_file = function(e){ $(e.target).parent().parent().remove(); }
  
  this.drop_handler = function(event) {
	if(event.dataTransfer.files.length==0){ return false; }
    event.stopPropagation();
    event.preventDefault();
    root.process_files(event.dataTransfer.files);
  }
	
  this.dragover_handler = function(event) {
    event.stopPropagation();
    event.preventDefault();
    //dropArea.className = 'hover';
  }
  
  this.process_files = function(filelist){
	$("#uploader_overlay").fadeIn();
    if(!filelist || !filelist.length || list.length){ return; }
    totalSize = 0;
    totalProgress = 0;
 
    for(var i=0; i<filelist.length;i++) {
	  if(filelist[i].type=="image/png" || filelist[i].type=="image/jpeg" || filelist[i].type=="image/jpg" ||filelist[i].type =="image/gif"){
        list.push(filelist[i]);
        totalSize += filelist[i].size;
	  }
    }
    root.upload_next();
  }
  
  
  this.upload_next = function() {
    if(list.length){
      //count.textContent = list.length - 1;
      dropArea.className = 'uploading';
      var nextFile = list.shift();
      root.upload_file(nextFile, status);
    }else{
	  $("#uploader_overlay").fadeOut();
      dropArea.className = '';
    }
  }
  
  
  this.upload_file = function(file, status) {
        // prepare XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST',path_php);
        xhr.onload = function(e) {
			console.log(e);
			//console.log(this.responseText);
            handleComplete(file.size);
			root.render_file(file);
			
        };
        xhr.onerror = function() {
            //result.textContent = this.responseText;
            handleComplete(file.size);
        };
        xhr.upload.onprogress = function(event) {
            handleProgress(event);
        }
        xhr.upload.onloadstart = function(event) {
        }
 
        // prepare FormData
        var formData = new FormData();
        formData.append('myfile', file);
        xhr.send(formData);
    }
	
	
	function handleProgress(event) {
        var progress = totalProgress + event.loaded;
        root.drawProgress(progress / totalSize);
    }
	
	function handleComplete(size) {
        totalProgress += size;
        root.drawProgress(totalProgress / totalSize);
        root.upload_next();
    }


    this.drawProgress = function(progress) {
	    width = $("#uploader_overlay").width();
		canvas.width = width;
        context.clearRect(0, 0, canvas.width, canvas.height); // clear context
        context.beginPath();
        context.strokeStyle = '#666666';
        context.fillStyle = '#FFFFFF';
        context.fillRect(0, 0, progress * width, 20);
        context.closePath();
        context.font = '16px Verdana';
        context.fillStyle = '#000';
        context.fillText('Cargando: ' + Math.floor(progress*100) + '%', 50, 15);
    }
	
	this.render_file = function(file){ 
	  var flag = true;
	  $("#"+$(el).attr("id")+" .isUploadedFile").each(function(){
	    if($(this).val()==file.name){ flag=false;  }  
	  });
	  if(flag){
	    $(el).children().append('<li style=" position:relative;"><input type="hidden" id="'+this.post_var+'[]" name="'+this.post_var+'[]" value="'+file.name+'" class="isUploadedFile"/><a href="javascript:void(0)" style=" background-color:#00a8ad; width:20px; height:20px; position:absolute; right:0"><img src="_lib/uploader/remove.png" width="20" height="20" class="onClickRemoveThis"/></a><img src="temp/'+file.name+'" width="130" height="90" class="onClickSetMain"/></li>');
	  }
	}
	
  init();
}
