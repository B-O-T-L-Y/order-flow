interface ToastMessage {
  id: number;
  message: string;
  type: "success" | "error" | "info";
}
