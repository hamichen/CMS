/*global tinymce:true */
/*global $ */
//editor tinymce.Editor
tinymce.PluginManager.add('imglist', function (editor) {

    function addImgList() {
        //圖片的id列表(mongodb id)
        var fileIdList = new Array();
        //排除非圖片的檔案
        $('#filelisted').find('>li').each(function(){

            var tmpStr = String($(this).find('>a').text());
            //正規式不分大小寫
            var tmpReg = new RegExp(".jpg|.png|.gif","i");
            //從副檔名來檢測是否為圖片
            if(tmpStr.match(tmpReg)){
                //console.log(tmpStr);
                //fileIdList.push(this.id);
                //檔名去除(檔案大小)
                fileIdList.push({
                    id : this.id,
                    text : tmpStr.slice(0,tmpStr.indexOf('(')).trim()
                });
            }
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
        for (y = 0; y < rowNum; y++) {

            gridHtml += '<tr>';

            for (x = 0; x < width; x++) {
                nowIndex = y * width + x;
                if(nowIndex < fileIdNum){
                    tmpMongoID = fileIdList[nowIndex].id;
                    //gridHtml += '<td><span>' + '<img id="'+tmpMongoID+'" class="is_select" style="width:100px;" src="/teach/index/download/'+tmpMongoID+'" width="80" />' + '</span>';
                    gridHtml += '<td style="text-align: center;"><div style="text-align: center;height: 25px;"><img src="/images/icon/ok.png" /></div>';
                    gridHtml += '<div style="text-align: center;">' + '<img id="'+tmpMongoID+'" class="is_select" src="/open-news/news/show-file/'+tmpMongoID+'.jpg?thumb=100,75" />' + '';
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
            title: '請選擇要插入的圖片',
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
                        var newGridHtml = '<div><ul class="mygallery-container">';
                        $('#myImgTable').find('img.is_select').each(function(){
                            //console.log(this);
                            newGridHtml += '<li>';
                            //加入附檔名是為了給lightbox進行判斷用，在teach/index/showimg進行移除
                            newGridHtml += '<a href="/open-news/news/show-file/'+this.id+'.jpg" data-lightbox="photoView">';
                            //圖片以縮圖來呈現(第一次瀏覽會建立縮圖，以後直接讀檔案)
                            newGridHtml += '<img src="/open-news/news/show-file/'+this.id+'.jpg?thumb=100,75" alt="img" />';
                            newGridHtml += '</a>';
                            newGridHtml += '</li>';
                            tmpNum += 1;
                        });
                        if(tmpNum > 0){
                            newGridHtml += '</ul></div><div style="clear: left;"></div>';
                            editor.insertContent(newGridHtml);
                            win.close();
                        }else{
                            alert('未選取任何圖片');
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
    //editor.addCommand("mceImgList", addImgList);

    editor.addButton('imglist', {
        icon: 'picture-o',
        tooltip: '插入圖片列表',
        onclick: addImgList
    });

    function addPArea(){
        editor.setContent(editor.getContent()+'<p></p>');
        //游標移到最後
        editor.selection.select(editor.getBody(), true);
        editor.selection.collapse(false);
    }

    function addHPArea(){
        editor.setContent('<p></p>'+editor.getContent());
        //游標移到最後
        //editor.selection.select(editor.getBody(), true);
        //editor.selection.collapse(false);
    }

    editor.addButton('addHP', {
        icon: 'plus-square-o',
        tooltip: '在最前方插入新段落(<p></p>)',
        onclick: addHPArea
    });

    editor.addButton('addP', {
        icon: 'square-o',
        tooltip: '在最下方插入新段落(<p></p>)',
        onclick: addPArea
    });
});