<h1>Seznam položek</h1>

<?if (!empty($this->errors_top)):?>
    <p class="error"><?=$this->errors_top?></p>
<?endif?>
<?if (!empty($this->top_message)):?>
    <p class="message"><?=$this->top_message?></p>
<?endif?>

<table class="w3_table">
    <tr>
        <th>Jméno položky</th><th>Zabraná plocha (m<sup>2</sup> / ks)</th>
        <th>Výbobce</th><th></th><th></th>
    </tr>
    <?foreach ($this->seznam as $row):?>
        <tr>
            <td><?=$row->name?></td>
            <td>
                <?if ($row->used):?>
                    <?=$row->area?>
                <?else:?>
                    <form method="post">
                        <input type="text" name="change_area[<?=$row->id?>]" value="<?=$row->area?>">
                        <input type="submit" name="action[change_area]" value="změnit">                    
                    </form>
                <?endif?>
            </td>
            <td><?=$row->vyrobce?> (<?=$row->country?>)</td>
            <td>
                <div class="rename_item hidden" id="rename_div_<?=$row->id?>">
                    <form method="post">
                        <input type="text" name="rename[<?=$row->id?>]" value="<?=$row->name?>">
                        <input type="submit" name="action[rename]" value="ok">
                    </form>                        
                </div>
                <button type="button" class="rename_butt" data-id="<?=$row->id?>">Přejmenovat</button>
            </td>
            <td>
                <div class="delete_warehouse">
                    <form method="post">                            
                        <input type="submit" name="action[delete][<?=$row->id?>]" value="smazat položku" <?=$row->used? 'disabled':''?>>
                    </form>
                </div>
            </td>
        </tr>
    <?endforeach?>
</table>

<h4>Vytvořit novou položku</h4>
<?if (isset($this->errors_bott) and count($this->errors_bott) != 0):?>
    <ul class="error">
        <?foreach ($this->errors_bott as $error):?>
            <li><?=$error?></li>
        <?endforeach?>
    </ul>
<?endif?>
<form method="post">
    <table id="new_item_form">
        <tr>
            <td><label for="new_name">Jméno</label></td>
            <td><input type="text" name="new_name" id="new_name" size="32"></td>
        </tr>
        <tr>
            <td><label for="new_area">Zabraná plocha (m<sup>2</sup> / ks)</label></td>
            <td><input type="text" name="new_area" id="new_area" size="10"></td>
        </tr>
        <tr>
            <td><label for="new_vyrobce">Výrobce</label></td>
            <td>
                <select name="new_vyrobce">
                    <option value="0" selected>Vyberte výrobce</option>
                    <?foreach ($this->vyrobci as $vyrobce):?>
                        <option value="<?=$vyrobce->id?>"><?=$vyrobce->name?> (<?=$vyrobce->adresa_radek?>)</option>
                    <?endforeach?>
                </select>
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="action[new]" value="Přidat"></td>
        </tr>
    </table>
</form>

<br/>
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>


<script src="<?=$this->webroot?>/js/seznam_polozek.js"></script>

