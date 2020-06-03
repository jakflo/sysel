<h1>Hledání položek</h1>

<?if (!empty($this->form_error)):?>
    <p class="error"><?=$this->form_error?></p>
<?endif?>

<form id="search_item" method="post">
    <div id="ware_stuff">        
        <div id="ware_all">
            <label for="ware_select_all">Vybrat všechny sklady</label>
            <input id="ware_select_all" type="checkbox" name="ware_all" value="1">
        </div>
        <div <?=$this->hide_warelist?> id="ware_list_cont">
            <ul class="bez_znacek" id="ware_list">
                <?foreach ($this->wares_list as $ware):?>
                <li>
                    <label for="wl_id_<?=$ware->id?>"><?=$ware->name?></label>
                    <input id="wl_id_<?=$ware->id?>" class="ware_list_cb" type="checkbox" name="warelist[<?=$ware->id?>]">
                </li>
                <?endforeach?>
            </ul>            
        </div>
    </div>
    <table class="w3_table" id="item_list">
        <tr class="head">
            <th>Položka</th>
            <th>Množství</th>
            <th></th>
        </tr>                
    </table>
    <button type="button" id="add_item">Přidat položku</button>
    <input type="submit" name="sent" value="Hledat">
</form>
    
<?
    if (!empty($this->search_result)) {
        require_once $this->env->get_param('root').'/pages/najit_polozku/nalezene_polozky.php';
    }
?>

<div class="hidden" id="saved_form"><?=$this->saved_form?></div>
<div class="hidden" id="saved_ware_list"><?=$this->saved_ware_list?></div>

<div class="hidden" id="item_template">
    <table>
    <tr class="item new" data-id="0">
        <td>
            <select name="items[0][item_id]">
                <option value="0" selected>Vyberte položku</option>
                <?foreach ($this->item_list as $item):?>
                    <option value="<?=$item->id?>"><?=$item->name?></option>
                <?endforeach?>
            </select>
        </td>
        <td>
            <input type="text" name="items[0][item_amount]">
        </td>
        <td>
            <button type="button" class="remove_item" data-id="0">Odebrat</button>
        </td>
    </tr>
    </table>
</div>

<br/>
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>

<script src="<?=$this->webroot?>/js/najit_polozku.js"></script>