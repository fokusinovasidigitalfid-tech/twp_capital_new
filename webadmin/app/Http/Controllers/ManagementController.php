<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use PDF;


class ManagementController extends Controller
{
    public function index($pdf = null)
    {
        return view('management.dashboard');
    }

    public function getApAging()
    {
        $data = DB::connection('ifcapb')->select("
        SELECT top 10 
            l.creditor_acct,
            c.name,
            SUM(l.Mbal_doc) as amount,
            SUM(SUM(l.Mbal_doc)) OVER () AS total_amount
        FROM mgr.ap_ledger l
        INNER JOIN mgr.ap_creditor c
            ON l.creditor_acct = c.creditor_acct
        WHERE l.entity_cd = '0001'
            AND l.class_cd = 'I'
            AND l.Mbal_doc > 0
        GROUP BY 
            l.creditor_acct, 
            c.name
        ORDER BY SUM(l.Mbal_doc) DESC
        ");

        return response()->json($data);
    }

    public function getArAging()
    {
        $data = DB::connection('ifcapb')->select("
        SELECT TOP 10
            l.debtor_acct,
            d.name,
            SUM(l.mbal_amt) AS amount,
            SUM(SUM(l.mbal_amt)) OVER () AS total_amount
        FROM mgr.ar_ledger l
        INNER JOIN mgr.ar_debtor d
            ON l.debtor_acct = d.debtor_acct
        WHERE l.entity_cd = '0001'
            AND l.class = 'I'
            AND l.mbal_amt > 0
        GROUP BY 
            l.debtor_acct,
            d.name
        ORDER BY SUM(l.mbal_amt) DESC;
        ");

        return response()->json($data);
    }

    public function getRevenueData(Request $request)
    {
        // Tangkap parameter 'year' dari request AJAX. 
        // Jika tidak ada, gunakan tahun saat ini sebagai default.
        $year = $request->input('year', date('Y'));

        // Gunakan '?' untuk parameter binding pada fyear
        $data = DB::connection('ifcapb')->select("
        SELECT
            p.aperiod,
            ISNULL(a.actual_amt,0)/1000000.0 actual,
            ISNULL(b.budget_amt,0)/1000000.0 budget
        FROM
        (
            SELECT 1 aperiod UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
            UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
            UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
        ) p

        LEFT JOIN
        (
            SELECT
                aperiod,
                ABS(SUM(trx_amt)) AS actual_amt
            FROM mgr.gl_postjnls
            WHERE entity_cd = '0001'
            AND fyear = ? 
            AND acct_cd IN (
                SELECT acct_cd
                FROM mgr.gl_chart
                WHERE acct_type = 'I'
                AND acct_cd NOT IN (
					'7100.100.02',
                    '7100.101.02',
                    '7100.102.02',
                    '7100.103.02',
                    '7100.104.02',
                    '7100.110.02',
                    '7100.111.02',
                    '7100.112.02',
                    '7100.199.02'
				)
            )
            GROUP BY aperiod
        ) a ON p.aperiod = a.aperiod

        LEFT JOIN
        (
            SELECT
                aperiod,
                ABS(SUM(trx_amt)) AS budget_amt
            FROM mgr.gl_budget_ver
            WHERE entity_cd = '0001'
            AND fyear = ? 
            AND acct_cd IN (
                SELECT acct_cd
                FROM mgr.gl_chart
                WHERE acct_type = 'I'
                AND acct_cd NOT IN (
					'7100.100.02',
                    '7100.101.02',
                    '7100.102.02',
                    '7100.103.02',
                    '7100.104.02',
                    '7100.110.02',
                    '7100.111.02',
                    '7100.112.02',
                    '7100.199.02'
				)
            )
            GROUP BY aperiod
        ) b ON p.aperiod = b.aperiod

        ORDER BY p.aperiod
        ", [$year, $year]); // Masukkan $year dua kali karena ada dua tanda '?' di query

        return response()->json($data);
    }

    public function getExpenseData(Request $request)
    {
        // Tangkap parameter 'year' dari request AJAX. 
        // Jika tidak ada, gunakan tahun saat ini sebagai default.
        $year = $request->input('year', date('Y'));

        // Gunakan '?' untuk parameter binding pada fyear
        $data = DB::connection('ifcapb')->select("
        SELECT
            p.aperiod,
            ISNULL(a.actual_amt,0)/1000000.0 actual,
            ISNULL(b.budget_amt,0)/1000000.0 budget
        FROM
        (
            SELECT 1 aperiod UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
            UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
            UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
        ) p

        LEFT JOIN
        (
            SELECT
                aperiod,
                ABS(SUM(trx_amt)) as actual_amt
            FROM mgr.gl_postjnls
            WHERE entity_cd = '0001'
            AND fyear = ? 
            AND acct_cd IN (
                SELECT acct_cd
                FROM mgr.gl_chart
                WHERE acct_type = 'E'
                AND acct_cd NOT IN (
					'8000.102.02',
                    '8000.103.02',
                    '8000.104.02',
                    '8000.105.02',
                    '8000.106.02',
                    '8000.107.02',
                    '8000.108.02',
                    '8000.109.02',
                    '8000.110.02',
                    '8000.111.02',
                    '8000.112.02',
                    '8000.113.02',
                    '8000.114.02',
                    '8000.115.02',
                    '8000.116.02',
                    '8000.117.02',
                    '8000.118.02',
                    '8000.199.02',
                    '8100.100.02',
                    '8100.101.02',
                    '8100.102.02',
                    '8100.103.02',
                    '8100.104.02',
                    '8100.105.02',
                    '8100.106.02',
                    '9000.991.02',
                    '9000.992.02',
                    '9999.001.02',
                    '9999.002.02',
                    '9999.003.02',
                    '9999.004.02',
                    '9999.005.02',
                    '9999.999.02'
				)
            )
            GROUP BY aperiod
        ) a ON p.aperiod = a.aperiod

        LEFT JOIN
        (
            SELECT
                aperiod,
                ABS(SUM(trx_amt)) budget_amt
            FROM mgr.gl_budget_ver
            WHERE entity_cd = '0001'
            AND fyear = ? 
            AND acct_cd IN (
                SELECT acct_cd
                FROM mgr.gl_chart
                WHERE acct_type = 'E'
                AND acct_cd NOT IN (
					'8000.102.02',
                    '8000.103.02',
                    '8000.104.02',
                    '8000.105.02',
                    '8000.106.02',
                    '8000.107.02',
                    '8000.108.02',
                    '8000.109.02',
                    '8000.110.02',
                    '8000.111.02',
                    '8000.112.02',
                    '8000.113.02',
                    '8000.114.02',
                    '8000.115.02',
                    '8000.116.02',
                    '8000.117.02',
                    '8000.118.02',
                    '8000.199.02',
                    '8100.100.02',
                    '8100.101.02',
                    '8100.102.02',
                    '8100.103.02',
                    '8100.104.02',
                    '8100.105.02',
                    '8100.106.02',
                    '9000.991.02',
                    '9000.992.02',
                    '9999.001.02',
                    '9999.002.02',
                    '9999.003.02',
                    '9999.004.02',
                    '9999.005.02',
                    '9999.999.02'
				)
            )
            GROUP BY aperiod
        ) b ON p.aperiod = b.aperiod

        ORDER BY p.aperiod
        ", [$year, $year]); // Masukkan $year dua kali karena ada dua tanda '?' di query

        return response()->json($data);
    }

    

    private const REVENUE_EXCLUDE = [
        '7100.100.02', '7100.101.02', '7100.102.02', '7100.103.02', '7100.104.02',
        '7100.110.02', '7100.111.02', '7100.112.02', '7100.199.02',
    ];

    private const EXPENSE_EXCLUDE = [
        '8000.102.02', '8000.103.02', '8000.104.02', '8000.105.02', '8000.106.02',
        '8000.107.02', '8000.108.02', '8000.109.02', '8000.110.02', '8000.111.02',
        '8000.112.02', '8000.113.02', '8000.114.02', '8000.115.02', '8000.116.02',
        '8000.117.02', '8000.118.02', '8000.199.02',
        '8100.100.02', '8100.101.02', '8100.102.02', '8100.103.02', '8100.104.02',
        '8100.105.02', '8100.106.02',
        '9000.991.02', '9000.992.02',
        '9999.001.02', '9999.002.02', '9999.003.02', '9999.004.02', '9999.005.02', '9999.999.02',
    ];

    /**
     * Ambil actual & budget per bulan (dalam juta) untuk acct_type tertentu.
     * Hasil di-keyBy aperiod supaya mudah digabung.
     */
    private function getGlSummary($year, $acctType, array $excludeAccts)
    {
        $ph = implode(',', array_fill(0, count($excludeAccts), '?'));

        $acctFilter = "
            SELECT acct_cd
            FROM mgr.gl_chart
            WHERE acct_type = ?
            AND acct_cd NOT IN ($ph)
        ";

        $sql = "
        SELECT
            p.aperiod,
            ISNULL(a.actual_amt,0)/1000000.0 actual,
            ISNULL(b.budget_amt,0)/1000000.0 budget
        FROM
        (
            SELECT 1 aperiod UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
            UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
            UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
        ) p
        LEFT JOIN
        (
            SELECT aperiod, ABS(SUM(trx_amt)) AS actual_amt
            FROM mgr.gl_postjnls
            WHERE entity_cd = '0001'
            AND fyear = ?
            AND acct_cd IN ($acctFilter)
            GROUP BY aperiod
        ) a ON p.aperiod = a.aperiod
        LEFT JOIN
        (
            SELECT aperiod, ABS(SUM(trx_amt)) AS budget_amt
            FROM mgr.gl_budget_ver
            WHERE entity_cd = '0001'
            AND fyear = ?
            AND acct_cd IN ($acctFilter)
            GROUP BY aperiod
        ) b ON p.aperiod = b.aperiod
        ORDER BY p.aperiod
        ";

        // Urutan binding harus sama dengan urutan '?' di query
        $bindings = array_merge(
            [$year, $acctType], $excludeAccts,   // subquery actual
            [$year, $acctType], $excludeAccts    // subquery budget
        );

        return collect(DB::connection('ifcapb')->select($sql, $bindings))->keyBy('aperiod');
    }

    public function getProfitData(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $revenue = $this->getGlSummary($year, 'I', self::REVENUE_EXCLUDE);
        $expense = $this->getGlSummary($year, 'E', self::EXPENSE_EXCLUDE);

        $data = [];
        for ($p = 1; $p <= 12; $p++) {
            $data[] = [
                'aperiod' => $p,
                'actual'  => $revenue[$p]->actual - $expense[$p]->actual,
                'budget'  => $revenue[$p]->budget - $expense[$p]->budget,
            ];
        }

        return response()->json($data);
    }

    public function getBankBalance()
{
    $data = DB::connection('ifcapb')->select("
    SELECT
        j.acct_cd,
        b.bank_name,
        j.amount,
        SUM(j.amount) OVER () AS total_amount
    FROM
    (
        SELECT
            acct_cd,
            SUM(trx_amt) AS amount
        FROM mgr.gl_postjnls
        WHERE entity_cd = '0001'
        AND acct_cd IN (
            SELECT acct_cd
            FROM mgr.gl_bank
            WHERE entity_cd = '0001'
			AND bank_cd like '%BCA%'
        )
        GROUP BY acct_cd
    ) j
    INNER JOIN
    (
        SELECT
            acct_cd,
            MIN(bank_name) AS bank_name
        FROM mgr.gl_bank
        WHERE entity_cd = '0001'
		AND bank_cd like '%BCA%'
        GROUP BY acct_cd
    ) b ON j.acct_cd = b.acct_cd
    ORDER BY j.amount DESC
    ");

    return response()->json($data);
}
}
