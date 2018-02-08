/*global $ */
/*global tinymce:true */
//editor tinymce.Editor
tinymce.PluginManager.add('filelist', function(editor) {

    /**
     * 檔案下載列表(非新視窗)
     */
//    function addFileList() {
//        var myTarget = $('#filelisted').clone();
//        myTarget.removeAttr('id').find('li>span').remove();
//        //模擬 outerHTML
//        //var outstr = myTarget.wrap('<div></div>').parent().html();
//        editor.insertContent(myTarget[0].outerHTML);
//
//	}

    /**
     * 檔案下載列表(非新視窗)
     */
    function addFileList() {
        outputfileList(false);
    }

    /**
     * 新視窗開啟的檔案下載列表
     */
    function addBlankFileList(){
        outputfileList(true);
    }

    /**
     * 輸出檔案列表
     * @param isBlank 是否需要新視窗開啟
     */
    function outputfileList(isBlank){
        //圖片的id列表(mongodb id)
        var fileIdList = new Array();
        //判斷副檔名，並記錄之
        $('#filelisted').find('>li').each(function(){
            var tmpStr = String($(this).find('>a').text());
            //檔名去除「(檔案大小)」及去除頭尾空白
            var tmpSearchIndex = tmpStr.indexOf('(');
            if(tmpSearchIndex != -1){
                tmpStr = tmpStr.slice(0,tmpSearchIndex).trim();
            }
            var tmpType = 'default';
            //正規式不分大小寫
            var tmpReg = new RegExp(".jpg|.png|.gif","i");
            //從副檔名來檢測是否為圖片
            if(tmpStr.match(tmpReg)){
                tmpType = 'img';
            }else{
                //取得副檔名
                var extIndex = tmpStr.lastIndexOf('.');
                if (extIndex != -1) {
                    tmpType = tmpStr.substr(extIndex+1, tmpStr.length);
                }
            }
            fileIdList.push({
                id : this.id,
                type : tmpType,
                text : tmpStr
            });
        });
        //視窗參照
        var win;
        //每列欄位數
        var width = 7;
        //資料筆數
        var fileIdNum = fileIdList.length;
        //列數
        var rowNum = Math.ceil(fileIdList.length/width);
        //output Html
        var gridHtml = '<div style="margin: 10px;"><table id="myImgTable" class="mygallery"><tbody>';
        //索引值
        var nowIndex = 0;
        //mongo id
        var tmpMongoID;
        var tmpNowtype;
        for (y = 0; y < rowNum; y++) {

            gridHtml += '<tr>';

            for (x = 0; x < width; x++) {
                nowIndex = y * width + x;
                if(nowIndex < fileIdNum){
                    tmpMongoID = fileIdList[nowIndex].id;
                    tmpNowtype = fileIdList[nowIndex].type;
                    //照片
                    if(tmpNowtype == 'img'){
                        gridHtml += '<td style="text-align: center;"><div style="text-align: center;height: 25px;"></div>';
                        gridHtml += '<div style="text-align: center;">' + '<img id="'+tmpMongoID+'" src="/open-news/news/show-file/'+tmpMongoID+'.jpg?thumb=40,34" />' + '';
                    //檔案
                    }else{
                        gridHtml += '<td style="text-align: center;"><div style="text-align: center;height: 25px;"></div>';
                        gridHtml += '<div style="text-align: center;">' + '<img id="'+tmpMongoID+'" src="/images/icon/'+tmpNowtype+'.jpg" />' + '';
                    }
                    gridHtml += '<br /><span style="font-size: 10px;">' + fileIdList[nowIndex].text + '</span></div></td>';
                }else{
                    //補欄位
                    gridHtml += '<td></td>';
                }
            }

            gridHtml += '</tr>';
        }

        gridHtml += '</tbody></table></div>';

        var iconsPanel = {
            type: 'container',
            html: gridHtml,
            onclick: function (e) {
                var myImg = $(e.target);
                //排除其他事件
                if(myImg.is('img')){
                    if(myImg.attr('src') != '/images/icon/ok.png'){
                        //console.log(e.target);
                        if(myImg.hasClass('is_select')){
                            myImg.removeClass('is_select');
                            myImg.parent().prev().find('img').remove();
                        }else{
                            myImg.addClass('is_select');
                            myImg.parent().prev().html('<img src="/images/icon/ok.png" />');
                        }
                    }
                }

            }
        };

        //開啟對話框
        win = editor.windowManager.open({
            title: '請選擇要製作成列表的檔案',
            width: 800,
            height: rowNum*130+50,
            resizable: "yes",
            inline : "yes",
            items: [
                iconsPanel
            ],
            buttons: [
                {
                    text: "全選",
                    onclick: function(){
                        $('#myImgTable').find('img').each(function(){
                            if($(this).attr('src') != '/images/icon/ok.png'){
                                $(this).addClass('is_select').parent().prev().html('<img src="/images/icon/ok.png" />');
                            }
                        });
                    }

                },
                {
                    text: "取消全選",
                    onclick: function(){
                        $('#myImgTable').find('img.is_select').each(function(){
                            $(this).removeClass('is_select').parent().prev().find('img').remove();
                        });
                    }

                },
                {
                    text: "確定",
                    onclick: function () {
                        //win.find("#myImgTable")
                        var tmpNum = 0;
                        var newGridHtml = '<div><ul class="list-group">';
                        $('#myImgTable').find('img.is_select').each(function(){
                            //console.log(this);
                            newGridHtml += '<li style="padding:5px 8px;" class="list-group-item">';
                            //新視窗直接顯示檔案
                            if(isBlank){
                                newGridHtml += '<a href="/open-news/news/showimg/'+this.id+'" target="blank">';
                            }else{
                            //直接下載檔案
                                newGridHtml += '<a href="/open-news/news/get-file/'+this.id+'">';
                            }
                            //取得檔名
                            newGridHtml += $(this).parent().find('>span').text()+'</a>';
                            newGridHtml += '</li>';
                            tmpNum += 1;
                        });
                        if(tmpNum > 0){
                            newGridHtml += '</ul></div><p> </p>';
                            editor.insertContent(newGridHtml);
                            win.close();
                        }else{
                            alert('尚未選取任何檔案');
                        }
                    }
                },
                {
                    text: "取消",
                    onclick: function () {
                        win.close();
                    }
                }
            ]
        });
    }

    //添加自訂一的命令到 tinymce.Editor ， 該命令可以透過execCommand來呼叫
	//editor.addCommand("mceFileList", addFileList);

	editor.addButton('filelist', {
		icon: 'list-alt',
		tooltip: '插入檔案下載列表',
		onclick: addFileList
	});

    editor.addButton('filelistBlank', {
		icon: 'list',
		tooltip: '插入檔案列表(新視窗開啟)',
		onclick: addBlankFileList
	});
});