<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
    // get the HTML
    ob_start();
    $num = 'CMD01-'.date('ymd');
    $nom = 'DUPONT Alphonse';
    $date = '01/01/2012';
?>
<style type="text/css">
<!--
    div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;}
    h1 { padding: 0; margin: 0; color: #DD0000; font-size: 7mm; }
    h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
-->
</style>
<page format="100x200" orientation="L" backcolor="#AAAACC" style="font: arial;">
    <div style="rotate: 90; position: absolute; width: 100mm; height: 4mm; left: 195mm; top: 0; font-style: italic; font-weight: normal; text-align: center; font-size: 2.5mm;">
        Ceci est votre e-ticket Ã  prÃ©senter au contrÃ´le d'accÃ¨s -
        billet gÃ©nÃ©rÃ© par <a href="http://html2pdf.fr/" style="color: #222222; text-decoration: none;">html2pdf</a>
    </div>
    <table style="width: 99%;border: none;" cellspacing="4mm" cellpadding="0">
        <tr>
            <td colspan="2" style="width: 100%">
                <div class="zone" style="height: 34mm;position: relative;font-size: 5mm;">
                    <div style="position: absolute; right: 3mm; top: 3mm; text-align: right; font-size: 4mm; ">
                        <b><?php echo $nom; ?></b><br>
                    </div>
                    <div style="position: absolute; right: 3mm; bottom: 3mm; text-align: right; font-size: 4mm; ">
                        <b>1</b> place <b>plein tarif</b><br>
                        Prix unitaire TTC : <b>45,00&euro;</b><br>
                        NÂ° commande : <b><?php echo $num; ?></b><br>
                        Date d'achat : <b><?php echo date('d/m/Y Ã  H:i:s'); ?></b><br>
                    </div>
                    <h1>Billet soirÃ©e spÃ©cial HTML2PDF</h1>
                    &nbsp;&nbsp;&nbsp;&nbsp;<b>Valable le <?php echo $date; ?> Ã  20h30</b><br>
                    <img src="./res/logo.gif" alt="logo" style="margin-top: 3mm; margin-left: 20mm">
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;">
                <div class="zone" style="height: 40mm;vertical-align: middle;text-align: center;">
                    <qrcode value="<?php echo $num."\n".$nom."\n".$date; ?>" ec="Q" style="width: 37mm; border: none;" ></qrcode>
                </div>
            </td>
            <td style="width: 75%">
                <div class="zone" style="height: 40mm;vertical-align: middle; text-align: justify">
                    <b>Conditions d'utilisation du billet</b><br>
                    Le billet est soumis aux conditions gÃ©nÃ©rales de vente que vous avez
                    acceptÃ©es avant l'achat du billet. Le billet d'entrÃ©e est uniquement
                    valable s'il est imprimÃ© sur du papier A4 blanc, vierge recto et verso.
                    L'entrÃ©e est soumise au contrÃ´le de la validitÃ© de votre billet. Une bonne
                    qualitÃ© d'impression est nÃ©cessaire. Les billets partiellement imprimÃ©s,
                    souillÃ©s, endommagÃ©s ou illisibles ne seront pas acceptÃ©s et seront
                    considÃ©rÃ©s comme non valables. En cas d'incident ou de mauvaise qualitÃ©
                    d'impression, vous devez imprimer Ã  nouveau votre fichier. Pour vÃ©rifier
                    la bonne qualitÃ© de l'impression, assurez-vous que les informations Ã©crites
                    sur le billet, ainsi que les pictogrammes (code Ã  barres 2D) sont bien
                    lisibles. Ce titre doit Ãªtre conservÃ© jusqu'Ã  la fin de la manifestation.
                    Une piÃ¨ce d'identitÃ© pourra Ãªtre demandÃ©e conjointement Ã  ce billet. En
                    cas de non respect de l'ensemble des rÃ¨gles prÃ©cisÃ©es ci-dessus, ce billet
                    sera considÃ©rÃ© comme non valable.<br>
                    <br>
                    <i>Ce billet est reconnu Ã©lectroniquement lors de votre
                    arrivÃ©e sur site. A ce titre, il ne doit Ãªtre ni dupliquÃ©, ni photocopiÃ©.
                    Toute reproduction est frauduleuse et inutile.</i>
                </div>
            </td>
        </tr>
    </table>
</page>
<?php
     $content = ob_get_clean();

    // convert
    require_once(dirname(__FILE__).'/../html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('ticket.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

