<script type="text/javascript">
    $(document).ready(function() {
        $('#file_upload').uploadify({
            'uploader'      : '<?php echo $this->Html->url("/upload/swf/uploadify.swf"); ?>',
            'script'        : '<?php echo $this->Html->url("/upload/imagens/addimg/" . $this->request->controller . "/" . $id); ?>',
            'cancelImg'     : '<?php echo $this->Html->url("/upload/img/cancel.png"); ?>',
            'folder'        : 'galerias',
            'multi'         : true,
            'fileExt'       : '*.jpg;*.gif;*.png',
            'buttonText'    : 'Selecione as Imagens',
            'width'         : 150,
            'onAllComplete' : function(event,data) {
                getJsonImages();
            },
            'onSelectOnce' : function() {
                $('#file_upload').uploadifyUpload();
            }
        });
    });
    
    
    function getJsonImages(){
        $("#imgAjuste").html("");
        
        $.getJSON("<?php echo $this->Html->url("/upload/imagens/loadimg/" . $this->request->controller . "/" . $id); ?>",
        {
            tags: "cat",
            tagmode: "any",
            format: "json"
        },
        function(data) {
            var items = [];
            var cabecalho = "<tr><th>Imagem</th>";
            $.each(data, function(key, val) {
                var id = val.Imagem.id;
                var url = val.Imagem.nome;
                var model = val.Imagem.model;
                var utilidade = val.Imagem.utilidade;
                var campos = utilidade.split(";");
                var urlPhp = "<?php echo $this->Html->url("/galerias/".$this->request->controller); ?>";
                var html = "<tr>";
                        
                html += "<td><img src='"+urlPhp+"/"+url+"' width='100' height='100' /></td>";
                        
                $.each(campos, function(i, value){
                    valor = value.split(",");
                    if(key == 0){
                        cabecalho += "<th>"+valor[0]+"</th>";
                    }
                    html += "<td><input type='radio' value='"+valor[1]+"' name='data[Imagem][utilidade]["+i+"]' /></td>";
                });
                html += "</tr>";
                items.push(html);
            });
            
            cabecalho += "</tr>";
            
            $('<table/>', {
                'class': 'uploadTable',
                html: cabecalho+items.join('')
            }).appendTo('#imgAjuste');
        })
    }
    getJsonImages();
</script>
<form action="<?php echo $this->Html->url("/upload/imagens/addimg/" . $this->request->controller . "/" . $id); ?>" method="post" class="uploadForm">
    <input id="file_upload" name="file_upload" type="file" />
</form>

<?php echo $this->Form->create('Imagem', array('id' => 'uploadForm')); ?>

<div id="imgAjuste"></div>

<p style="text-align:center">
    <?php echo $this->Form->button('Enviar', array('type' => 'submit','class'=>'button','escape' => true)); ?>
</p>
<?php echo $this->Form->end(); ?>