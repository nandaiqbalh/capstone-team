<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Broadcast;

use App\Http\Controllers\Controller;
use App\Models\Api\Mahasiswa\Broadcast\ApiBroadcastModel;
use Illuminate\Http\Request;

class ApiBroadcastController extends Controller
{
    public function index()
    {
        try {
            $rs_broadcast = ApiBroadcastModel::getDataWithPagination();

            foreach ($rs_broadcast as $broadcast) {
                $created_date = $broadcast->created_date;

                // Menghitung selisih waktu dari sekarang dengan waktu pembuatan broadcast
                $time_diff = time() - strtotime($created_date);

                // Mengonversi selisih waktu menjadi menit
                $minutes = round($time_diff / 60);

                if ($minutes < 60) {
                    // Jika kurang dari satu jam
                    $postDate = "$minutes Menit yang lalu";
                } elseif ($minutes < 24 * 60) {
                    // Jika kurang dari 24 jam
                    $hours = round($minutes / 60);
                    $postDate = "$hours Jam yang lalu";
                } else {
                    // Jika lebih dari 24 jam
                    // Ambil tanggal, bulan, dan tahun
                    $date_parts = explode('-', date('d-F-Y', strtotime($created_date)));
                    // Konversi bulan ke bahasa Indonesia
                    $date_parts[1] = $this->convertMonthToIndonesian($date_parts[1]);
                    // Gabungkan kembali tanggal, bulan, dan tahun
                    $postDate = implode(' ', $date_parts);
                }

                // Menambahkan postDate ke objek broadcast
                $broadcast->postDate = $postDate;

                // Convert HTML in keterangan to plain text with original formatting
                if (isset($broadcast->keterangan)) {
                    $broadcast->keterangan = $this->convertHtmlToOriginalText($broadcast->keterangan);
                }
            }

            $this->addImageUrlToBroadcasts($rs_broadcast);

            $response = $this->successResponse('Berhasil mendapatkan data.', ['rs_broadcast' => $rs_broadcast]);

            return response()->json($response);
        } catch (\Exception $e) {
            $response = $this->failureResponse('Mohon periksa kembali koneksi internet Anda!');

            return response()->json($response);
        }
    }

    public function broadcastHome()
    {
        try {
            $rs_broadcast = ApiBroadcastModel::getDataWithHomePagination();

            foreach ($rs_broadcast as $broadcast) {
                $created_date = $broadcast->created_date;

                // Menghitung selisih waktu dari sekarang dengan waktu pembuatan broadcast
                $time_diff = time() - strtotime($created_date);

                // Mengonversi selisih waktu menjadi menit
                $minutes = round($time_diff / 60);

                if ($minutes < 60) {
                    // Jika kurang dari satu jam
                    $postDate = "$minutes Menit yang lalu";
                } elseif ($minutes < 24 * 60) {
                    // Jika kurang dari 24 jam
                    $hours = round($minutes / 60);
                    $postDate = "$hours Jam yang lalu";
                } else {
                    // Jika lebih dari 24 jam
                    // Ambil tanggal, bulan, dan tahun
                    $date_parts = explode('-', date('d-F-Y', strtotime($created_date)));
                    // Konversi bulan ke bahasa Indonesia
                    $date_parts[1] = $this->convertMonthToIndonesian($date_parts[1]);
                    // Gabungkan kembali tanggal, bulan, dan tahun
                    $postDate = implode(' ', $date_parts);
                }

                // Menambahkan postDate ke objek broadcast
                $broadcast->postDate = $postDate;

                // Convert HTML in keterangan to plain text with original formatting
                if (isset($broadcast->keterangan)) {
                    $broadcast->keterangan = $this->convertHtmlToOriginalText($broadcast->keterangan);
                }
            }

            $this->addImageUrlToBroadcasts($rs_broadcast);

            $response = $this->successResponse('Berhasil mendapatkan data.', ['rs_broadcast' => $rs_broadcast]);

            return response()->json($response);
        } catch (\Exception $e) {
            $response = $this->failureResponse('Mohon periksa kembali koneksi Internet Anda!');

            return response()->json($response);
        }
    }

    public function detailBroadcastApi(Request $request)
    {
        try {
            $id = $request->input('id');
            $broadcast = ApiBroadcastModel::getDataById($id);

            if (!empty($broadcast)) {
                $broadcast->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);

                // Convert HTML in keterangan to plain text with original formatting
                if (isset($broadcast->keterangan)) {
                    $broadcast->keterangan = $this->convertHtmlToOriginalText($broadcast->keterangan);
                }

                $response = $this->successResponse('Berhasil mendapatkan data.', ['broadcast' => $broadcast]);
            } else {
                $response = $this->failureResponse('Mohon periksa kembali koneksi Internet Anda!');
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response = $this->failureResponse('Mohon periksa kembali koneksi Internet Anda!');

            return response()->json($response);
        }
    }

    private function convertHtmlToOriginalText($html)
    {
        $replacements = [
            // Bold tags
            '/<b>(.*?)<\/b>/' => '$1',
            '/<strong>(.*?)<\/strong>/' => '$1',
            // Italic tags
            '/<i>(.*?)<\/i>/' => '$1',
            '/<em>(.*?)<\/em>/' => '$1',
            // Underline tags
            '/<u>(.*?)<\/u>/' => '__$1__',
            // Line breaks
            '/<br\s*\/?>/' => "\n",
            // Paragraph tags
            '/<p>(.*?)<\/p>/' => "\n$1\n",
            // Remove other tags but keep the content
            '/<[^>]+>/' => '',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $html = preg_replace($pattern, $replacement, $html);
        }

        return $html;
    }

    private function addImageUrlToBroadcasts(&$broadcasts)
    {
        foreach ($broadcasts as $key => $broadcast) {
            $broadcasts[$key]->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);
        }
    }

    private function getBroadcastImageUrl($broadcast)
    {
        if (!empty($broadcast->broadcast_image_name)) {
            $imageUrl = url($broadcast->broadcast_image_path . $broadcast->broadcast_image_name);
        } else {
            $imageUrl = url('img/broadcast/default_broadcast.png');
        }

        return $imageUrl;
    }

    private function successResponse($statusMessage, $data)
    {
        return [
            'success' => true,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    private function failureResponse($statusMessage)
    {
        return [
            'success' => false,
            'status' => $statusMessage,
            'data' => null,
        ];
    }
}
