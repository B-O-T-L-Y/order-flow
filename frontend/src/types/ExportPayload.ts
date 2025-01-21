interface ExportPayload {
  format: "csv" | "xlsx";
  selectAll: boolean;
  selectedOrders: number[];
  excludedOrders: number[];

  user_id?: string | number;
  status?: string;
  start_date?: string;
  end_date?: string;
  min_amount?: number | null;
  max_amount?: number | null;
}
