<h1>Seznam skladů</h1>

<?if (!$this->seznam_skladu):?>
    <h3>Žádné sklady dosud nebyly vytvořeny</h3>
<?else:?>
    <?if (!empty($this->errors_top)):?>
        <p class="error"><?=$this->errors_top?></p>
    <?endif?>
    <?if (!empty($this->top_message)):?>
        <p class="message"><?=$this->top_message?></p>
    <?endif?>
    <table class="w3_table">
        <tr>
            <th>Jméno skladu</th>
            <th>Volná plocha (m<sup>2</sup>)</th>
            <th>Vytvořeno</th>
            <th></th><th></th>
        </tr>    
        <?foreach ($this->seznam_skladu as $sklad):?>
            <tr>
                <td><?=$sklad->name?></td>
                <td><?=$sklad->area_left?> / <?=$sklad->area?></td>
                <td><?=$sklad->created?></td>
                <td>
                    <div class="rename_warehouse hidden" id="rename_div_<?=$sklad->id?>">
                        <form method="post">
                            <input type="text" name="rename[<?=$sklad->id?>]" value="<?=$sklad->name?>">
                            <input type="submit" name="action[rename]" value="ok">
                        </form>                        
                    </div>
                    <button type="button" class="rename_butt" data-id="<?=$sklad->id?>">Přejmenovat</button>
                </td>
                <td>
                    <div class="delete_warehouse">
                        <form method="post">                            
                            <input type="submit" name="action[delete][<?=$sklad->id?>]" value="smazat sklad" <?=$sklad->is_empty? '':'disabled'?>>
                        </form>                        
                    </div>                    
                </td>
            </tr>
        <?endforeach?>
    </table>
<?endif?>
    
<h4>Vytvořit nový sklad</h4>
<?if (count($this->errors_bott) != 0):?>
    <ul class="error">
        <?foreach ($this->errors_bott as $error):?>
            <li><?=$error?></li>
        <?endforeach?>
    </ul>
<?endif?>
<form method="post">
    <table id="new_warehouse_form">
        <tr>
            <td><label for="new_name">Jméno</label></td>
            <td><input type="text" name="new_name" id="new_name" size="32"></td>
        </tr>
        <tr>
            <td><label for="new_area">Použitelná plocha (m<sup>2</sup>)</label></td>
            <td><input type="text" name="new_area" id="new_area" size="10"></td>
        </tr>
        <tr>
            <td><input type="submit" name="action[new]" value="Přidat"></td>
        </tr>
    </table>
</form>

<br/>
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>

    
<script src="<?=$this->get_webroot()?>/js/seznam_skladu.js"></script>
