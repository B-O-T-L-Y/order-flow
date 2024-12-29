interface Order {
  id: number,
  status: string,
  amount: number,
  user: {
    name: string,
    email: string,
  },
  products: Array<{
    id: number,
    name: string,
    pivot: {
      quantity: number,
      price: number,
    }
  }>;
}
