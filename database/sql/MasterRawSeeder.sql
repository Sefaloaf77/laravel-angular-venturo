INSERT INTO `m_customer` (`id`, `name`, `email`, `phone_number`, `date_of_birth`, `photo`, `is_verified`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 'Wahyu',    'wahyuagung26@gmail.com',   '+628563155681',    NULL,   NULL,   1,  '2022-11-28 07:44:50',  '2022-11-28 07:44:50',  NULL,   0,  0,  0),
(2, 'Agung',    NULL,   NULL,   NULL,   NULL,   1,  '2022-11-28 07:44:56',  '2022-11-28 07:44:56',  NULL,   0,  0,  0),
(3, 'Tribawono',    NULL,   NULL,   NULL,   NULL,   1,  '2022-11-28 07:45:02',  '2022-11-28 07:45:02',  NULL,   0,  0,  0);


INSERT INTO `m_product` (`id`, `m_product_category_id`, `name`, `price`, `description`, `photo`, `is_available`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 1,  'Mie Instan',   15000,  NULL,   NULL,   1,  '2022-11-28 07:18:38',  '2022-11-28 07:18:38',  NULL,   0,  0,  0),
(2, 1,  'Mie Rebus',    25000,  NULL,   NULL,   1,  '2022-11-28 07:19:07',  '2022-11-28 07:19:07',  NULL,   0,  0,  0),
(3, 2,  'Es Teh',   10000,  NULL,   NULL,   1,  '2022-11-28 07:19:22',  '2022-11-28 07:19:22',  NULL,   0,  0,  0),
(4, 2,  'Es Jeruk', 12000,  NULL,   NULL,   1,  '2022-11-28 07:19:34',  '2022-11-28 07:19:34',  NULL,   0,  0,  0);


INSERT INTO `m_product_category` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 'Makanan',  '2022-11-28 07:17:53',  '2022-11-28 07:17:53',  NULL,   0,  0,  0),
(2, 'Minuman',  '2022-11-28 07:17:58',  '2022-11-28 07:17:58',  NULL,   0,  0,  0);


INSERT INTO `m_product_detail` (`id`, `m_product_id`, `type`, `description`, `price`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 1,  'Level',    'Level 1',  1000,   '2022-11-28 07:18:38',  '2022-11-28 07:18:38',  NULL,   0,  0,  0),
(2, 1,  'Level',    'Level 2',  2000,   '2022-11-28 07:18:38',  '2022-11-28 07:18:38',  NULL,   0,  0,  0),
(3, 2,  'Toping',   'Telur',    2500,   '2022-11-28 07:19:07',  '2022-11-28 07:19:07',  NULL,   0,  0,  0),
(4, 2,  'Toping',   'Ayam', 3000,   '2022-11-28 07:19:07',  '2022-11-28 07:19:07',  NULL,   0,  0,  0);


INSERT INTO `m_promo` (`id`, `name`, `status`, `expired_in_day`, `nominal_percentage`, `nominal_rupiah`, `term_conditions`, `photo`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 'Voucher Member Baru',  'voucher',  30, NULL,   20000,  NULL,   NULL,   '2022-11-28 07:19:58',  '2022-11-28 07:19:58',  NULL,   0,  0,  0),
(2, 'Voucher Member Get Member',    'voucher',  30, NULL,   5000,   NULL,   NULL,   '2022-11-28 07:20:17',  '2022-11-28 07:20:17',  NULL,   0,  0,  0);


INSERT INTO `m_voucher` (`id`, `m_customer_id`, `m_promo_id`, `start_time`, `end_time`, `total_voucher`, `nominal_rupiah`, `photo`, `description`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 2,  2,  '2022-11-01',   '2022-11-30',   15, 10000,  NULL,   NULL,   '2022-11-28 07:45:21',  '2022-11-28 07:45:21',  NULL,   0,  0,  0),
(2, 1,  1,  '2022-10-01',   '2022-10-31',   5,  20000,  NULL,   NULL,   '2022-11-28 07:45:34',  '2022-11-28 07:45:34',  NULL,   0,  0,  0);
