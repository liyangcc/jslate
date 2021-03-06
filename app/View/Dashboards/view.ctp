<?php
$zid=1;
foreach ($dashboard['Dbview'] as $dbview){
    $zid++;
    $id = $dbview['id'];
    $wid = 'id-'.String::uuid();
    $code = str_replace('${wid}', $wid, $dbview['code']);
    $style = $user == null ? 'style="cursor:auto;"' : '';
    echo "<textarea id='code$id' style='display: none;'>$code</textarea>";
    echo "<div class='dragbox' id='dragbox_$id'
        style=' overflow: hidden; position: absolute; z-index: $zid; left: ".$dbview['left']."px; top: ".$dbview['top']."px; width: ".($dbview['width'])."px; height: ".($dbview['height'])."px;'>
            <div class='header' $style>
                <span>&nbsp;";
    if($user != null){
        echo $this->Html->link('x', "/dbviews/delete/$id", array('style' =>'float: right; margin-left: 10px;'), 'Are you sure you want to remove this widget?');
        echo $this->Html->link('edit', "/dbviews/edit/$id", array('style' =>'float: right;'));
    }
    echo "</span>";
    echo "</div>
            <div class='dragbox-content' id='view$id' style='clear: both; width: ".($dbview['width']-10)."px; height: ".($dbview['height']-30)."px;'>$code</div>
        </div>";
}
?>
<?php if($user != null): ?>
<script type='text/javascript'>
    $(function(){
        $(".dragbox").draggable({
            handle: ".header",
            grid: [10, 10],
            stop: function(event, ui){
                var id = ui.helper.context.id.split('_')[1];
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->Html->url('/dbviews/update/')?>"+id,
                    data: {data:{left: ui.position.left, top: ui.position.top}},
                    success: function(msg){
                    }
                });
            }
        });
        $(".dragbox").resizable({
            grid: [10, 10],
            stop: function(event, ui){

                var id = ui.helper.context.id.split('_')[1];
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->Html->url('/dbviews/update/')?>"+id,
                    data: {data:{width: ui.size.width, height: ui.size.height}},
                    success: function(msg){
                        $('#view'+id).height((ui.size.height-30)+'px')
                        $('#view'+id).width((ui.size.width-10)+'px')
                        $('#view'+id).html($('#code'+id).val())
                    }
                });
            }
        });
    });
</script>
<?php endif; ?>
