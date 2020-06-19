<h1>Položky objednávky <?=$this->order_id?> ve skladech</h1>
<?=$this->get_session_msg('error_msg', 'error')?>

<?require_once $this->env->get_param('root').'/pages/najit_polozku/nalezene_polozky.php'?>

<?if ($this->nic_nebylo_nalezeno):?>
    <h2>Žádna položka nebyla nalezena!</h2>
<?endif?>

<h2>Přiřadit nalezené položky k objednávce</h2>
<?if (count($this->sklady_kde_je_alespon_1_hledana_polozka) > 0):?>
    <?if (count($this->missing_items) > 0):?>
        <p class="red">Pozor! Některé položky nebyly nalezeny. Tyto položky bude možné přidat později.</p>
    <?endif?>
    <h3>Použít sklady:</h3>
    <form method="post" id="use_wares">
        <ul class="bez_znacek">
            <li>
                <input id="cb_use_all_wares" type="checkbox" name="use_all_wares" value="1" checked="">
                <label for="cb_use_all_wares">Vybrat všechny sklady</label>
            </li>
            <div class="hidden" id="select_wares">
                <?foreach ($this->sklady_kde_je_alespon_1_hledana_polozka as $ware):?>
                    <li>
                        <input id="cb_wares_<?=$ware->id?>" type="checkbox" name="use_ware[<?=$ware->id?>]" value="1">
                        <label for="cb_wares_<?=$ware->id?>"><?=$ware->name?></label>                
                    </li>
                <?endforeach?>
            </div>        
        </ul>
        <input type="submit" name="sent" value="Přiřadit">
    </form>
<?else:?>
    <h3>Žádna položka nebyla nalezena!</h3>
<?endif?>
<br />
<a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>

<script src="<?=$this->webroot?>/js/order_search_item.js"></script>

