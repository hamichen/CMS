/*global jQuery */
(function ($) {
    /**
     * 簡單樹
     * 原始程式出處 https://github.com/zgs225/easy-tree
     * for sfs 自訂修改版
     * @param options   相關選項
     * @constructor
     * @return object   jqueryWrapper
     */
    $.fn.EasyTree = function (options) {
        var defaults = {
            //是否為移動模式
            isMoveMode : false ,
            i18n: {
                deleteNull: '請選擇要刪除的項目。',
                deleteConfirmation: '您確定要刪除這個項目嗎？',
                confirmButtonLabel: '確定',
                editNull: '請選擇要編輯的項目。',
                editMultiple: '一次只能編輯一個項目',
                addMultiple: '請選擇一個項目來新增',
                collapseTip: '收起分支',
                expandTip: '展開分支',
                selectTip: '選取',
                unselectTip: '取消選取',
                editTip: '編輯',
                addTip: '新增',
                deleteTip: '刪除',
                cancelButtonLabel: '取消'
            },
            //icon 設定，請使用 fontawesome icon
            setting :{
                branchOpenIcon : 'fa-folder-open-o',
                branchCloseIcon : 'fa-folder-o',
                leafIcon : 'fa-file-o'
            }
        };

        //將傳入的參數物件，合併到defaults物件
        options = $.extend(defaults, options);

        //這裡的 this 代表的元素的 jquery包裹物件
        var easyTree = this;

        // 展開或合併(事件監聽採委派)
        easyTree.on('click','li.parent_li > span > span', function (e) {
            var jQSapn = $(this);
            var children = jQSapn.parent().parent('li.parent_li').find(' > ul > li');
            //目錄內無資料
            if(children.length<1 ){
                if(jQSapn.hasClass(options.setting.branchCloseIcon)){
                    jQSapn.attr('title', options.i18n.collapseTip)
                        .addClass(options.setting.branchOpenIcon)
                        .removeClass(options.setting.branchCloseIcon);
                }else{
                    jQSapn.attr('title', options.i18n.expandTip)
                        .addClass(options.setting.branchCloseIcon)
                        .removeClass(options.setting.branchOpenIcon);
                }
            }else{
                if (children.is(':visible')) {
                    children.hide('normal');
                    jQSapn.attr('title', options.i18n.expandTip)
                        .addClass(options.setting.branchCloseIcon)
                        .removeClass(options.setting.branchOpenIcon);
                } else {
                    children.show('fast');
                    jQSapn.attr('title', options.i18n.collapseTip)
                        .addClass(options.setting.branchOpenIcon)
                        .removeClass(options.setting.branchCloseIcon);
                }
            }
            //事件不要冒泡(不往上層節點傳遞)
            e.stopPropagation();
        });

        //選取，目前僅使用單選功能(註冊委派事件，節省效能，同時所有新增的節點可以立即套用該事件監聽器)
        easyTree.on('click','li > span > a',function(e){
            //console.log($(this).text());
            var li = $(this).parent().parent();

            //移動模式時
            if(defaults.isMoveMode){
                if (li.hasClass('li_selected')) {
                    alert('無法移動到同一個位置!!\n\n請重新選擇其他位置');
                }else{
                    //判斷是否為目錄
                    if(li.attr('data-typeid')== 0){
                        //非鎖定節點
                        if(li.attr('data-statusid')>0){
                            //觸發移動事件
                            easyTree.trigger('easyTree.move', li);
                        }else{
                            alert('您選擇的目錄是校務評鑑固定的資料，無法進行異動。\n\n請重新選擇');
                        }
                    }else{
                        alert('請選擇一個目錄作為移動的目標');
                    }
                }
                return false;
            }

            //正常選取模式
            if (li.hasClass('li_selected')) {
                $(this).attr('title', options.i18n.selectTip);
                li.removeClass('li_selected');
                //觸發自訂「取消選取」事件
                easyTree.trigger('easyTree.unselected');
            }else {
                //移除其他已選取項目，並恢復title預設值
                easyTree.find('li.li_selected').removeClass('li_selected').find('> span > a').attr('title', options.i18n.selectTip);
                $(this).attr('title', options.i18n.unselectTip);
                li.addClass('li_selected');
                //觸發自訂「選取」事件(第2個參數為Array or PlainObject，這裡傳回jquery wrapper後，接收者會只收到html element)
                easyTree.trigger('easyTree.selected', li);
            }
            //事件不要冒泡(不往上層節點傳遞)
            e.stopPropagation();
        });

        $.fn.EasyTree.buildTree = function(){
            //針對 easyTree 包裹集合中 符合 ul > li 的元素 進行遍歷，將有子物件的節點加入目錄icon，沒有子目錄的節點加入檔案icon
            $.each(easyTree.find('ul > li'), function() {

                var findLi = $(this);
                //text紀錄li內的文字欄位
                var text;
                //是目錄
                if(findLi.data('typeid') == 0){
                    findLi.addClass('parent_li');

                    var tmpHtmlHead = '<span><span class="fa '+options.setting.branchCloseIcon+' fa-lg" title="'+options.i18n.expandTip+'"></span><a href="javascript: void(0);" title="'+options.i18n.selectTip+'">';
                    var tmpHtmlEnd = '</a></span>';

                    if(findLi.is('li:has(ul)')) {
                        //加入 class parent_li 表示父節點，然後再找出子項目
                        var children = findLi.find(' > ul');
                        //隱藏子節點(li)項目
                        children.find('> li').hide();
                        //將子項目移除Dom，待設定好標籤後再存回
                        children.remove();
                        text = findLi.text();
                        //加入目錄 icon
                        findLi.html(tmpHtmlHead+text+tmpHtmlEnd).append(children);
                    }else{
                        text = findLi.text();
                        //加入目錄 icon
                        findLi.html(tmpHtmlHead+text+tmpHtmlEnd);
                    }
                }else{
                    text = findLi.text();
                    //加入 file icon
                    findLi.html('<span><span class="fa '+options.setting.leafIcon+' fa-lg"></span><a href="javascript: void(0);" title="'+options.i18n.selectTip+'">'+text+'</a></span>');
                }
            });
        };

        /**
         * 新增節點
         * @param insertLi  節點內容
         */
        $.fn.EasyTree.addItem = function(insertLi){
            var selected = easyTree.find('li.li_selected');
            if (selected.length <= 0) {
                alert("尚未選取項目");
            } else if (selected.length > 1) {
                alert("發現重複選取項目，請重新載入頁面。");
            } else {
                //有<ul>子元素
                if(selected.find(' > ul').length > 0){
                    //目錄設為開啟
                    selected.find(' > span > span').addClass(options.setting.branchOpenIcon).removeClass(options.setting.branchCloseIcon);
                    selected.find(' > ul').append(insertLi);
                }else{
                    //無<ul>子項目
                    selected.find(' > span > span').addClass(options.setting.branchOpenIcon).removeClass(options.setting.branchCloseIcon);
                    selected.append($('<ul></ul>')).find(' > ul').append(insertLi);
                }
            }
        };

        /**
         * 修改節點
         * @param insertLi  節點內容
         */
        $.fn.EasyTree.updateItem = function(insertLi){
            var selected = easyTree.find('li.li_selected');
            if (selected.length <= 0) {
                alert("尚未選取項目");
            } else if (selected.length > 1) {
                alert("發現重複選取項目，請重新載入頁面。");
            } else {
                //目錄
                if(insertLi.typeID == 0){
                    selected.addClass('parent_li');
                    selected.find('>span>span').removeClass('glyphicon-file').addClass(options.setting.branchCloseIcon).attr('title',options.i18n.expandTip);
                }else{
                    selected.removeClass('parent_li');
                    selected.find('>span>span').removeClass().addClass('glyphicon glyphicon-file').removeAttr('title');
                }
                selected.find('>span>a').text(insertLi.title);
                selected.attr('data-typeid',insertLi.typeID);
                //觸發自訂「工具列狀態更新」事件
                easyTree.trigger('toolbar.refresh');
            }
        };

        /**
         * 移動選單(含所有子節點)
         * @param sId int 來源節點
         * @param tId int 目標節點
         */
        $.fn.EasyTree.moveItem = function(sId,tId){
            var sourceLi = $('#m'+sId).remove();
            var targetLi = $('#m'+tId);
            var targetSapn = targetLi.find('>span>span');
            //判斷是否已展開分枝，若未展開，則觸發展開事件
            if(targetSapn.hasClass(options.setting.branchCloseIcon)){
                targetSapn.trigger('click');
            }
            //有<ul>子元素
            if(targetLi.find(' > ul').length > 0){
                targetLi.find(' > ul').append(sourceLi);
            }else{
                //無<ul>子項目
                targetLi.append($('<ul></ul>')).find(' > ul').append(sourceLi);
            }
        };

        /**
         * 設定是否移動模式
         * @param isMove bool
         */
        $.fn.EasyTree.setMoveMode = function(isMove){
            defaults.isMoveMode = isMove;
        };

        /**
         * 排序某目錄下的子選單
         * @param sID int 目錄ID
         * @param sortArr  array 新的排序列表(["item_117", "item_121", "item_122"])須轉為m117
         */
        $.fn.EasyTree.sortItem = function(sID,sortArr){
            var SourceLi = easyTree.find('#m'+sID+'>ul');
            //依序移動新序列
            for (var i = 0; i < sortArr.length; i++) {
                var tmpId = String(sortArr[i]).substr(5);
                easyTree.find('#m'+tmpId).appendTo(SourceLi);
            }
        };

       /**
         * 刪除選取的節點
         */
        $.fn.EasyTree.delItem = function(){
            //檢查parent是否還有節點，若無則需要刪除整個<ul></ul>
            var tmpSelect = easyTree.find('li.li_selected');
            //如果有兩個以上子節點
            if(tmpSelect.parent().find('>li').length > 1){
                tmpSelect.remove();
            }else{
                tmpSelect.parent().remove();
            }
        };

        /**
         * 展開全部分支
         */
        $.fn.EasyTree.openAll = function () {
            easyTree.find('li.parent_li').each(function(){
                $(this).find('> ul > li').show('fast').end()
                    .find('> span > span.fa').attr('title', options.i18n.collapseTip)
                    .addClass(options.setting.branchOpenIcon).removeClass(options.setting.branchCloseIcon);

            });
        };

        /**
         * 收起全部分支
         */
        $.fn.EasyTree.closeAll = function(){
            easyTree.find('li.parent_li').each(function(){
                $(this).find('> ul > li').hide('fast').end()
                    .find('> span > span.fa').attr('title', options.i18n.expandTip)
                    .addClass(options.setting.branchCloseIcon).removeClass(options.setting.branchOpenIcon);

            });
        };

        //傳回 jquery包裹物件，方便程式碼進行 Chianing
        return easyTree;
    };

})(jQuery);
