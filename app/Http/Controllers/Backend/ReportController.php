<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    /**
     * Display booking report page (Backend)
     */
    public function BookingReport()
    {
        return view('backend.report.booking_report');
    }

    /**
     * Search bookings by date range (Backend)
     */
    public function SearchByDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Search bookings within date range
        $bookings = Booking::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        return view('backend.report.booking_search_date', compact('bookings', 'startDate', 'endDate'));
    }
}