use Dompdf\Dompdf;

class PdfController extends BaseController
{
    public function index()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('<h1>Lista de compras:</h1>
                           <ul>
                               <li>Item 1</li>
                               <li>Item 2</li>
                               <li>Item 3</li>
                           </ul>');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("exemplo.pdf", array("Attachment" => false));
    }
}
