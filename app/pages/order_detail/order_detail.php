<h1>Detail objednávky</h1>

<h2>Základní informace</h2>
<div id="basic_nfo">
    <table class="simple_borderless">
        <tr>
            <th>Č. objednávky</th>
            <td><?=$this->order_id?></td>
        </tr>
        <tr>
            <th>Přidáno</th>
            <td><?=$this->basic_nfo->added?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?=$this->basic_nfo->status_name?></td>
        </tr>
        <?if (!empty($this->basic_nfo->note)):?>
            <tr>
                <th>Poznámka</th>
                <td><?=$this->basic_nfo->note?></td>
            </tr>
        <?endif?>
    </table>
</div>

<h2>Informace o klientovy</h2>
<div id="client_nfo">
    <table class="simple_borderless">
        <?if (!empty($this->client_nfo->company_name)):?>
            <tr>
                <th>Společnost</th>
                <td><?=$this->client_nfo->company_name?></td>
            </tr>
        <?endif?>
            <tr>
                <th>Jméno</th>
                <td><?=$this->client_nfo->comb_name?></td>
            </tr>
            <tr>
                <th>Adresa</th>
                <td><?=$this->client_nfo->comb_address?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?=$this->client_nfo->email?></td>
            </tr>
            <tr>
                <th>Telefon</th>
                <td><?=$this->client_nfo->phone?></td>
            </tr>
    </table>
</div>

<h2>Položky v objednávce</h2>
<?if ($this->items):?>
    <div id="items_in_order">
        <table class="w3_table">
            <tr>
                <th>Jméno položky</th><th>Množství</th>
            </tr>
            <?foreach ($this->items as $item):?>
                <tr>
                    <td><?=$item->it_name?></td>
                    <td><?=$item->pocet?></td>
                </tr>
            <?endforeach?>
        </table>
    </div>
<?else:?>
    <p>Objednávka neobsahuje žádné položky</p>
<?endif?>

<br />
<div id="nav_butts">
    <a href="<?=$this->get_webroot()?>/orders"><button type="button">Zpět</button></a>
    <?if ($this->basic_nfo->status == 1):?>
        <a href="<?=$this->get_webroot()?>/order_search_item/<?=$this->order_id?>"><button type="button">Najít položky</button></a>
    <?endif?>
    <a href="<?=$this->get_webroot()?>"><button type="button">Domů</button></a>
</div>
