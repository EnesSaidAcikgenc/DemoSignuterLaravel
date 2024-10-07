<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature; // Modeli ekliyoruz
use Illuminate\Support\Facades\Storage; // Storage sınıfını ekliyoruz

class SignatureController extends Controller
{
    public function store(Request $request)
    {
        // Formdan gelen adı ve soyadı al
        $name = $request->input('name');
        $lastName = $request->input('last_name');

        // Base64 kodlu imzayı al
        $signatureData = $request->input('signature');

        // Base64 ile gelen veriyi çöz
        $signatureImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData));

        // Dosya adını oluştur
        $imageName = 'signature_' . time() . '.png';

        // Resmi public/storage/signatures dizinine kaydet
        Storage::put('public/signatures/' . $imageName, $signatureImage);

        // Veritabanına kaydet
        Signature::create([
            'name' => $name,
            'last_name' => $lastName,
            'image' => $imageName
        ]);

        // Başarılı bir yanıt döndür
        return response()->json(['success' => 'İmza başarıyla kaydedildi!']);
    }

    public function main()
    {
        // Veritabanından tüm imzaları al ve main sayfasına gönder
        $signatures = Signature::all();
        return view('pdfProject.main', compact('signatures'));
    }

    public function index()
    {

        // İmza kaydetme sayfasını döndür
        $signatures = Signature::all();
        return view('pdfProject.index', compact('signatures'));
    }
    public function downloadPdf()
    {
        // İmza kayıtlarını veritabanından çekiyoruz
        $signatures = Signature::all();

        // pdfProject.pdf_view adında bir view oluşturup verileri ona gönderiyoruz
        $pdf = \PDF::loadView('pdfProject.pdf.signature', compact('signatures'));

        // PDF olarak indirmek için çıktı döndür
        return $pdf->download('signatures.pdf');
    }
}
