<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use SimpleXMLElement;

class UploadController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return view('welcome', compact('contacts'));
    }
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $file = $request->file('file');
        $filePath = $file->getPathname();
        $fileContent = file_get_contents($filePath);
        $xml = new SimpleXMLElement($fileContent);

        foreach ($xml->contact as $contact) {
            $data = explode('+', (string)$contact, 2); // seperating name and phone 

            if (count($data) == 2) {
                Contact::create([ // inserting to database
                    'name' => $data[0],
                    'phone' => '+' .$data[1],
                ]);
            }
        }
        return back()->with('msg', 'File uploaded successfully');
    }
}
