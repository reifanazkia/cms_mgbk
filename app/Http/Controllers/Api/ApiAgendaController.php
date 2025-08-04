<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Agenda",
 *     type="object",
 *     required={"id", "title", "description", "start_datetime", "location", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Seminar Nasional Teknologi"),
 *     @OA\Property(property="description", type="string", example="Deskripsi lengkap acara."),
 *     @OA\Property(property="start_datetime", type="string", format="date-time", example="2025-09-10T09:00:00"),
 *     @OA\Property(property="end_datetime", type="string", format="date-time", example="2025-09-10T12:00:00"),
 *     @OA\Property(property="event_organizer", type="string", example="Universitas XYZ"),
 *     @OA\Property(property="location", type="string", example="Auditorium A, Kampus Utama"),
 *     @OA\Property(property="register_link", type="string", example="https://example.com/register"),
 *     @OA\Property(property="youtube_link", type="string", example="https://youtube.com/example"),
 *     @OA\Property(property="type", type="string", example="Webinar"),
 *     @OA\Property(property="status", type="string", enum={"Soldout", "Open"}, example="Open"),
 *     @OA\Property(
 *         property="speakers",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=3),
 *             @OA\Property(property="name", type="string", example="Budi Santoso"),
 *             @OA\Property(property="title", type="string", example="CTO di ABC Tech")
 *         )
 *     ),
 *     @OA\Property(property="image", type="string", example="agenda/example.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ApiAgendaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/agendas",
     *     operationId="getAllAgendas",
     *     tags={"Agenda"},
     *     summary="Menampilkan daftar agenda",
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar agenda",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="List agenda"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Agenda")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            // Ambil semua agenda beserta pembicara
            $agendas = Agenda::with('speakers')->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'List agenda',
                'data' => $agendas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data agenda',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/agendas/{id}",
     *     operationId="getAgendaById",
     *     tags={"Agenda"},
     *     summary="Menampilkan detail satu agenda berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Agenda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail agenda ditemukan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail agenda"),
     *             @OA\Property(property="data", ref="#/components/schemas/Agenda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agenda tidak ditemukan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Agenda tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Terjadi kesalahan server",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mengambil data agenda"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $agenda = Agenda::with('speakers')->find($id);

            if (!$agenda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agenda tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail agenda',
                'data' => $agenda
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data agenda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
