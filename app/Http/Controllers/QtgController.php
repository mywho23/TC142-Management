<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\QtgChapter;
use App\Models\QtgUpload;
use App\Models\QtgSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class QtgController extends Controller
{

    public function index($device_id, $year = null)
    {
        // Tentukan tahun aktif
        $currentYear = $year ?? date('Y');

        // Pastikan ada entry session untuk tahun ini (opsional tapi direkomendasikan)
        \App\Models\QtgSession::firstOrCreate([
            'device_id' => $device_id,
            'year'      => $currentYear
        ]);

        // Ambil semua tahun yg sudah ada di tb_qtg_session
        $years = QtgSession::where('device_id', $device_id)
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->toArray();

        // Safety: pastikan tahun aktif ada di dropdown
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
            sort($years);
        }

        // Ambil chapter & upload per tahun
        $chapters = QtgChapter::where('device_id', $device_id)
            ->orderBy('order_number', 'asc')
            ->get();

        $uploads = QtgUpload::where('device_id', $device_id)
            ->where('year', $currentYear)
            ->get()
            ->keyBy('chapter_id');

        return view('pages.qtg.index', [
            'device'       => Device::find($device_id),
            'chapters'     => $chapters,
            'uploads'      => $uploads,
            'years'        => $years,
            'current_year' => $currentYear,
        ]);
    }

    public function storeUpload(Request $req, $device_id)
    {
        $validated = $req->validate([
            'chapter_id' => 'required|integer',
            'year'       => 'required|integer',
            'filepath'   => 'required|mimes:pdf|max:50000',
            'result'     => 'required|in:passed,failed',
            'note'       => 'nullable|string',
        ]);

        $chapterId = $validated['chapter_id'];
        $year      = $validated['year'];

        // 🔍 1. CARI DATA LAMA (agar tidak bikin baris baru)
        $existing = QtgUpload::where('device_id', $device_id)
            ->where('chapter_id', $chapterId)
            ->where('year', $year)
            ->first();

        // 🔥 Siapkan folder file
        $deviceName  = \App\Models\Device::find($device_id)->device_name;
        $chapterCode = \App\Models\QtgChapter::find($chapterId)->chapter_code;

        $path = 'template/assets/file/qtg/' . $deviceName . '/' . $year;

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }

        // 🧹 2. Hapus file lama kalau ada
        if ($existing && $existing->filepath) {
            $oldPath = public_path($existing->filepath);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // 📥 3. Upload file baru
        $file = $req->file('filepath');
        $filename = $chapterCode . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $filename);

        // 📝 4. Simpan atau update record
        if ($existing) {
            // UPDATE
            $existing->update([
                'filepath' => $path . '/' . $filename,
                'result'   => $validated['result'],
                'note'     => $validated['note'] ?? null,
            ]);
        } else {
            // INSERT
            QtgUpload::create([
                'device_id'  => $device_id,
                'chapter_id' => $chapterId,
                'year'       => $year,
                'filepath'   => $path . '/' . $filename,
                'result'     => $validated['result'],
                'note'       => $validated['note'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'QTG berhasil diupload!');
    }


    public function updateUpload(Request $req, $device_id, $id)
    {
        $upload = QtgUpload::findOrFail($id);

        $validated = $req->validate([
            'result' => 'required|in:passed,failed',
            'note'   => 'nullable|string',
            'filepath' => 'nullable|mimes:pdf|max:50000',
        ]);

        $device = Device::findOrFail($device_id);
        $chapter = QtgChapter::findOrFail($upload->chapter_id);

        $folderPath = public_path("template/assets/file/qtg/{$device->device_name}/{$upload->year}");

        if ($req->hasFile('filepath')) {

            if ($upload->filepath && file_exists(public_path($upload->filepath))) {
                unlink(public_path($upload->filepath));
            };

            $filename = $chapter->chapter_code . ".pdf";
            $req->file('filepath')->move($folderPath, $filename);
            $upload->filepath = "template/assets/file/qtg/{$device->device_name}/{$upload->year}/{$filename}";
        };
        $upload->result = $validated['result'];
        $upload->note   = $validated['note'];
        $upload->save();

        return back()->with('success', 'PDF berhasil diperbarui!');
    }

    public function deleteUpload($device_id, $id)
    {
        $upload = QtgUpload::findOrFail($id);

        // Hapus file
        if ($upload->filepath) {
            $file = public_path($upload->filepath);
            if (File::exists($file)) {
                File::delete($file);
            }
        }

        // Hapus row DB
        $upload->delete();

        return back()->with('success', 'Data berhasil dihapus!');
    }

    public function streamPdf($device_id, $id)
    {
        // Ambil data upload
        $upload = QtgUpload::findOrFail($id);

        // Path file di public/
        $path = public_path($upload->filepath);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Stream file PDF langsung ke browser
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="qtg.pdf"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function tahun(Request $request, $device_id)
    {
        $year = $request->year;

        return redirect()->route('qtg.chapter.index', [
            'device_id' => $device_id,
            'year' => $year
        ]);
    }

    public function addYear($device_id)
    {
        $newYear = date('Y') + 1;

        QtgSession::firstOrCreate([
            'device_id' => $device_id,
            'year'      => $newYear
        ]);

        return back();
    }

    public function print($device_id, $year)
    {
        //ambil data device
        $device = Device::findOrFail($device_id);

        //ambil semua chapter device
        $chapters = QtgChapter::where('device_id', $device_id)
            ->orderBy('order_number', 'asc')
            ->get();

        //ambil upload tahun
        $uploads = QtgUpload::where('device_id', $device_id)
            ->where('year', $year)
            ->get()
            ->keyBy('chapter_id'); //KeyBy untuk cek chapter mana yang udah upload

        //hitungan progres
        $totalcompleted = $uploads->count();
        $passed = $uploads->where('result', 'passed')->count();
        $failed = $uploads->where('result', 'failed')->count();

        //progres (chapter belum upload tidak termasuk)
        $progress = $totalcompleted > 0
            ? round(($passed / $totalcompleted) * 100, 2) : 0;

        return view('pages.qtg.print', [
            'device' => $device,
            'year' => $year,
            'chapters' => $chapters,
            'uploads' => $uploads,
            'totalcompleted' => $totalcompleted,
            'passed' => $passed,
            'failed' => $failed,
            'progress' => $progress
        ]);
    }
}
