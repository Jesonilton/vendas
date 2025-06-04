import { ISeller } from "./SellerTypes"

export interface ISale {
  id: number, 
  description: string, 
  seller_id: number, 
  amount: number, 
  sale_date: string, 
  seller: ISeller 
}

export interface IFormSale {
  id?: number, 
  description: string, 
  seller_id: number, 
  amount: number, 
  sale_date: string, 
  seller: ISeller 
}

export interface ISalesFilter {
  seller_id: number, 
}
