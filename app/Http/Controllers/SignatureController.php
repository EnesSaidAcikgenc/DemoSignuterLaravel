<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Signature; // Modeli ekliyoruz
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Storage sınıfını ekliyoruz
use Barryvdh\DomPDF\Facade as PDF;

class SignatureController extends Controller
{
    public function store(Request $request)
    {

        // Kullanıcı ID'sini al
        $userId = Auth::id();

        // Formdan gelen adı ve soyadı al
        $name = $request->input('name');
        $lastName = $request->input('last_name');
        $kimlik = $request->input('kimlik');
        $cinsiyet = $request->input('cinsiyet');
        $universite= $request->input('universite');

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
            'kimlik' => $kimlik,
            'cinsiyet' => $cinsiyet,
            'universite' => $universite,
            'image' => $imageName,
            'user_id' => $userId // Kullanıcı ID'sini ekle
        ]);

        // Başarılı bir yanıt döndür
        return response()->json(['success' => 'İmza başarıyla kaydedildi!']);
    }

    public function main()
    {
        // Veritabanından kullanıcıya ait imzaları al
        $userId = Auth::id();
        $signatures = Signature::where('user_id', $userId)->get();

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
        $userId = auth()->id(); // Şu anki kullanıcının kimliğini alın
        $signature = Signature::where('user_id', $userId)->first();

        // Kullanıcının kaydı yoksa hata döndürün
        if (!$signature) {
            return redirect()->back()->with('error', 'Kullanıcıya ait imza bulunamadı.');
        }

        // PDF'i oluştur ve indir
        $pdf = \PDF::loadView('pdfProject.pdf.signature', compact('signature'));
        return $pdf->download('user_signature_form.pdf');
    }


    public function login()
    {
        return view('pdfProject.login');
    }
    public function loginp(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('main');
        }
        else{
            return redirect()->route('login');
        }
    }
    public function register()
    {
        return view('pdfProject.register');
    }

    public function registerp(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('main');
    }
}
