import { Component, OnInit } from '@angular/core';
import { MerchantApiService } from '../../../core/services/merchant-api.service';
import { Product } from '../../../core/models/product.model';
import { Router } from '@angular/router';
import { ToastService } from '../../../core/services/toast.service';

@Component({
  selector: 'portal-merchant-products-listing',
  templateUrl: './merchant-products-listing.component.html',
  styleUrls: ['./merchant-products-listing.component.scss']
})
export class MerchantProductsListingComponent implements OnInit {

  products: Product[];
  loading: boolean;
  total: number;
  limit = 10;
  offset: number;
  currentPage = 1;

  constructor(private merchantService: MerchantApiService, private router: Router, private toastService: ToastService) {}

  ngOnInit(): void {
    this.offset = 0;
    this.refresh();
  }

  refresh(): void {
    this.pageChange();
    this.loading = true;
    this.merchantService.getProducts(this.limit, this.offset).subscribe((value) => {
      this.products = value.data;
      this.total = value.total;
      this.loading = false;
    });
  }

  openAddProductForm(): void {
    this.merchantService.getProducts(this.limit, this.offset).subscribe(response => {
      this.toastService.success('Produkt erfolgreich gespeichert');
    });
    // this.router.navigate(['merchant/products/details']);
  }

  editProduct(product: Product): void {
    this.router.navigate(['merchant/products/details', product.id]);
  }

  pageChange(): void {
    this.offset = (this.currentPage - 1) * 10;
  }
}
