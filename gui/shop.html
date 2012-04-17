<script type="text/javascript">
{literal}
function getItemNum(id){
    $.get("/ajax/getItemInfo.php?view=quantity&item="+id, function(data){
        $("#buy"+id+"_num").html(data);
        $("#sell"+id+"_num").html(data);
    });
}
    
function buyItem(id){
    var num = $("input[name=buy"+id+"_num").val();
    var shop = $("input[name=buy"+id+"_mode]").val();
    $.ajax({
        type: "POST",
        url: "/ajax/buyItem.php?shoptype="+shop,
        data: "quantity="+num+"&goodsid="+id+"&submit=Mua",
        success: function(msg){
            var arr = msg.split("|");
            if (arr[0] == '1'){
                getItemNum(id);
                $("#buy"+id+"-tips").removeClass("msg-info msg-error msg-success").addClass('msg-success');
                $("#buy"+id+"-tips").html("<strong>Thành công:</strong> "+arr[1]+"<a href=\"#\" title=\"Remove\" class=\"remove-btn\" onclick=\"messageRemove(this)\">Xóa</a>").fadeIn('slow');	
            } else {
                $("#buy"+id+"-tips").removeClass("msg-info msg-error msg-success").addClass('msg-error');
                $("#buy"+id+"-tips").html("<strong>Thất bại:</strong> "+arr[1]+"<a href=\"#\" title=\"Remove\" class=\"remove-btn\" onclick=\"messageRemove(this)\">Xóa</a>").fadeIn('slow');
            }
        }
    });    
}   

function sellItem(id){
    var num  = $("input[name=sell"+id+"_num]").val();
    var shop = $("input[name=sell"+id+"_mode]").val();
    $.ajax({
        type: "POST",
        url: "/ajax/sellItem.php?shoptype="+shop,
        data: "quantity="+num+"&goodsid="+id+"&submit=Bán",
        success: function(msg){
            var arr = msg.split("|");
            if (arr[0] == '1'){
                getItemNum(id);
                $("#sell"+id+"-tips").removeClass("msg-info msg-error msg-success").addClass('msg-success');
                $("#sell"+id+"-tips").html("<strong>Thành công:</strong> "+arr[1]+"<a href=\"#\" title=\"Remove\" class=\"remove-btn\" onclick=\"messageRemove(this)\">Xóa</a>").fadeIn('slow');	
            } else {
                $("#sell"+id+"-tips").removeClass("msg-info msg-error msg-success").addClass('msg-error');
                $("#sell"+id+"-tips").html("<strong>Thất bại:</strong> "+arr[1]+"<a href=\"#\" title=\"Remove\" class=\"remove-btn\" onclick=\"messageRemove(this)\">Xóa</a>").fadeIn('slow');
            }
        }
    });    
}  
{/literal}
</script>

    <div id="contentpanel">
        <div class="box open">
            <div class="box-title"><div>
                Bạn muốn mua gì?
                <a href="#" class="box-toggle"></a>
            </div></div>
            
        {if $itemToSell}
            <div class="box-content">
            	{foreach from=$itemToSell item=item}
                    {assign var=payTypeId value=$item.paytype}
                    {assign var=itemId value=$item.goodsid}
                    
                    <div id="buy{$itemId}" class="box_item">
                        <div class="box_item_image">
                            <span class='box_item_quantity item_num' id="buy{$itemId}_num" >{$playerBag.$itemId}</span>
                            <img src='http://play.lacai.de/res/item/n_item_{$itemId}.jpg'>
                        </div>
                        <div class="box_item_button">
                            <form method='post' action='/ajax/buyItem.php?type=oshop'>
                            <input type='hidden' name='item' value='{$itemId}'  />
                            <input type='hidden' name='buy{$itemId}_mode' value='1'  />
                            <input type='text' name='buy{$itemId}_num' value='' style='width: 36px; background: {if $item.quantity == 0}#ccc{else}#fff{/if};'/>
                            <br/>
                            <input id="buy{$itemId}_button" type='submit' name='submit' style='width: 52px; margin-top: 2px;' value='Mua' onclick="javascript:buyItem({$itemId});return false;" />
                            </form>
                        </div>
                        <div class="box_item_desc">
                            <h2>{$item.goodsname}</h2>
                            <p>01 {$item.goodsname} có giá là {$item.price} {$payType.$payTypeId}.</p>
                        </div>
                        <p id="buy{$itemId}-tips" class="msg-info" style="clear: both; margin-right: 18px; display:none;"><a href="#" title="Remove" class="remove-btn">&nbsp;</a></p>
                    </div>
                {/foreach}
                <div style="clear: both;"></div>
                <p class="msg-info"><strong>Chú ý:</strong> trước khi click vào nút <strong>ĐỔI</strong>, các thành chủ <strong>bắt buộc phải out khỏi game</strong>!</p>
            </div>
        {else}
            <div class="box-content">
                <p class="msg-info">Xin lỗi, hiện Trụ Vương không thiếu thốn gì, nên không cần mua vật phẩm gì từ quý thành chủ!</p>
            </div>
        {/if}        
        </div>
        
        <div class="box open">
            <div class="box-title"><div>
                Bạn muốn bán vật phẩm gì?
                <a href="#" class="box-toggle"></a>
            </div></div>
            
        {if $itemToBuy}
            <div class="box-content">
            	{foreach from=$itemToBuy item=item}
                    {assign var=payTypeId value=$item.paytype}
                    {assign var=itemId value=$item.goodsid}
                    
                    <div id="sell{$itemId}" class="box_item">
                        <div class="box_item_image">
                            <span class='box_item_quantity item_num' id="sell{$itemId}_num" >{$playerBag.$itemId}</span>
                            <img src='http://play.lacai.de/res/item/n_item_{$itemId}.jpg'>
                        </div>
                        <div class="box_item_button">
                            <form method='post' action='/ajax/buyItem.php?type=oshop'>
                            <input type='hidden' name='item' value='{$itemId}'  />
                            <input type='hidden' name='sell{$itemId}_mode' value='1'  />
                            <input type='text' name='sell{$itemId}_num' value='' style='width: 36px; background: {if $item.quantity == 0}#ccc{else}#fff{/if};'/>
                            <br/>
                            <input id="sell{$itemId}_button" type='submit' name='submit' style='width: 52px; margin-top: 2px;' value='Bán' onclick="javascript:sellItem({$itemId});return false;" />
                            </form>
                        </div>
                        <div class="box_item_desc">
                            <h2>{$item.goodsname}</h2>
                            <p>01 {$item.goodsname} có giá là {$item.price} {$payType.$payTypeId}.</p>
                        </div>
                        <p id="sell{$itemId}-tips" class="msg-info" style="clear: both; margin-right: 18px; display:none;"><a href="#" title="Remove" class="remove-btn">&nbsp;</a></p>
                    </div>
                {/foreach}
                <div style="clear: both;"></div>
                <p class="msg-info"><strong>Chú ý:</strong> trước khi click vào nút <strong>ĐỔI</strong>, các thành chủ <strong>bắt buộc phải out khỏi game</strong>!</p>
            </div>
        {else}
            <div class="box-content">
                <p class="msg-info">Xin lỗi, hiện Trụ Vương không thiếu thốn gì, nên không cần mua vật phẩm gì từ quý thành chủ!</p>
            </div>
        {/if}        
        </div>
    </div>
    <div class="clear"></div>
    