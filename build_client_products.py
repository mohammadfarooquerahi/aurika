import csv
import os
import zipfile
import shutil

csv_path = r"C:\Users\DC\BIC data\Aurika\aurika-products-client-data.csv"
output_dir = r"C:\Users\DC\BIC data\Aurika"

headers = [
    "ID", "Type", "SKU", "Name", "Published", "Is featured?", "Visibility in catalog",
    "Short description", "Description", "In stock?", "Stock", "Allow customer reviews?",
    "Regular price", "Sale price", "Categories", "Tags",
    "Attribute 1 name", "Attribute 1 value(s)", "Attribute 1 visible", "Attribute 1 global",
    "Attribute 2 name", "Attribute 2 value(s)", "Attribute 2 visible", "Attribute 2 global",
    "Parent", "Position", "Images"
]

rows = [
    # 1. Lavender Scented Candle
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-01", "Name": "Lavender Scented Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Fragrance: Lavender | Burning Time: 24 Hours | Wax: Natural Paraffin Blend",
        "Description": "A beautifully crafted scented candle with a soothing lavender fragrance, designed to add warmth and elegance to your space.\n\n• Fragrance: French Lavender\n• Burn Time: 24 Hours\n• Wax Type: Premium Paraffin Blend\n• Variants Available: Rose, Sunflower, Lavender\n• Handcrafted in Pakistan",
        "In stock?": 1, "Stock": 50, "Allow customer reviews?": 1,
        "Regular price": "999", "Sale price": "", "Categories": "Scented Candles", "Tags": "Lavender, Scented Candles, Handmade, Calm",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 0, "Images": ""
    },

    # 2. Sea Shell Ocean Candle (Artistic Edition)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-02", "Name": "Sea Shell Ocean Candle (Artistic Edition)",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Fragrance: Ocean | Burning Time: 10 Hours | Hand-carved Sea Shell Design",
        "Description": "An artistic sea-shell inspired candle with a refreshing ocean fragrance, designed to combine decorative coastal appeal with a fragrant sensory experience.\n\n• Fragrance: Crisp Ocean Breeze\n• Burn Time: 10 Hours\n• Design: Sea Shell Mold\n• Hand-poured with love",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "699", "Sale price": "", "Categories": "Scented Candles", "Tags": "Ocean, Sea Shell, Decorative, Coastal",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 1, "Images": ""
    },

    # 3. Sea Shell Ocean Candle (Pack of 1 / Pack of 2) - Variable
    {
        "ID": "", "Type": "variable", "SKU": "AUR-CND-03", "Name": "Sea Shell Ocean Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Fragrance: Ocean | Burning Time: 10 Hours | Available in 1 Piece or 2 Pieces Pack",
        "Description": "A charming sea-shell candle with a refreshing ocean fragrance, perfect for adding a decorative touch to your space. Choose single piece or value pair.\n\n• Fragrance: Ocean\n• Burn Time: 10 Hours (each)\n• Pack Options: 1 Piece (Rs. 599) / 2 Pieces (Rs. 1,100)",
        "In stock?": 1, "Stock": 60, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "Scented Candles", "Tags": "Sea Shell, Ocean, Pack Options",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece, 2 Pieces", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 2, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-03-1", "Name": "Sea Shell Ocean Candle - 1 Piece",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "1 Piece Sea Shell Ocean Candle",
        "In stock?": 1, "Stock": 30, "Allow customer reviews?": 1,
        "Regular price": "599", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-CND-03", "Position": 1, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-03-2", "Name": "Sea Shell Ocean Candle - 2 Pieces",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "2 Pieces Sea Shell Ocean Candle (Save Rs. 98)",
        "In stock?": 1, "Stock": 30, "Allow customer reviews?": 1,
        "Regular price": "1100", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "2 Pieces", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-CND-03", "Position": 2, "Images": ""
    },

    # 4. Sea Shell Ocean Candle Set (2 Pieces with Tray)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-04", "Name": "Sea Shell Ocean Candle Set (2 Pieces with Tray)",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Includes 2 Sea Shell Candles + Luxury Decorative Tray | Fragrance: Ocean",
        "Description": "A two-piece sea-shell candle set with a decorative tray and refreshing ocean fragrance, perfect for gifting or elegant home décor.\n\n• What's Included: 2 Sea Shell Candles + 1 Matching Tray\n• Fragrance: Fresh Ocean Breeze\n• Burn Time: 10 Hours each\n• Ideal for gifting & living room display",
        "In stock?": 1, "Stock": 25, "Allow customer reviews?": 1,
        "Regular price": "1499", "Sale price": "", "Categories": "Scented Candles", "Tags": "Gift Set, Sea Shell, Tray, Ocean",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 3, "Images": ""
    },

    # 5. Sea Shell Ocean Candle (Set of 2 Pieces)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-05", "Name": "Sea Shell Ocean Candle (Set of 2 Pieces)",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Set of 2 Artistic Sea Shell Candles | Fragrance: Ocean | Burn Time: 10 Hours Each",
        "Description": "A set of two artistic sea-shell candles with a refreshing ocean fragrance, adding charm, warmth, and coastal elegance to your space.\n\n• Quantity: 2 Individual Candles\n• Fragrance: Ocean\n• Burn Time: 10 Hours (individual)",
        "In stock?": 1, "Stock": 35, "Allow customer reviews?": 1,
        "Regular price": "1299", "Sale price": "", "Categories": "Scented Candles", "Tags": "Sea Shell, Pair, Ocean",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 4, "Images": ""
    },

    # 6. Peony Flower Scented Candle (1 Piece / 3 Pieces)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-CND-06", "Name": "Peony Flower Scented Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Delicate flower-shaped candle. Available in Lavender, Rose, and Lily scents.",
        "Description": "A delicate peony flower-shaped candle available in lavender, rose, and lily fragrances, perfect for adding a floral touch to your space.\n\n• Fragrance Options: Lavender, Rose, Lily\n• Burn Time: 6 Hours (each)\n• Pricing: 1 Piece (Rs. 199) / 3 Pieces Pack (Rs. 499)",
        "In stock?": 1, "Stock": 80, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "Scented Candles", "Tags": "Peony, Flower, Floral, Lavender, Rose",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece, 3 Pieces Pack", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Lavender, Rose, Lily", "Attribute 2 visible": 1, "Attribute 2 global": 1,
        "Parent": "", "Position": 5, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-06-1", "Name": "Peony Flower Scented Candle - 1 Piece",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "1 Piece Peony Flower Candle",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "199", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Lavender", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-06", "Position": 1, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-06-3", "Name": "Peony Flower Scented Candle - 3 Pieces Pack",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "3 Pieces Peony Flower Candle Pack (Save Rs. 98)",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "499", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "3 Pieces Pack", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Lavender", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-06", "Position": 2, "Images": ""
    },

    # 7. Daisy Flower Candle (1 Piece / 3 Pieces)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-CND-07", "Name": "Daisy Flower Candle",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Charming daisy-shaped candle available in Rose, Lily, Lavender, Sunflower scents.",
        "Description": "A charming daisy-shaped candle available in rose, lily, lavender, and sunflower fragrances.\n\n• Fragrances: Rose, Lily, Lavender, Sunflower\n• Burn Time: 5 Hours (individual)\n• Pricing: 1 Piece (Rs. 150) / 3 Pieces Pack (Rs. 399)",
        "In stock?": 1, "Stock": 80, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "Scented Candles", "Tags": "Daisy, Flower, Sunflower, Rose",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece, 3 Pieces Pack", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose, Lily, Lavender, Sunflower", "Attribute 2 visible": 1, "Attribute 2 global": 1,
        "Parent": "", "Position": 6, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-07-1", "Name": "Daisy Flower Candle - 1 Piece",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "1 Piece Daisy Flower Candle",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "150", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-07", "Position": 1, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-07-3", "Name": "Daisy Flower Candle - 3 Pieces Pack",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "3 Pieces Daisy Flower Candle Pack",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "399", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "3 Pieces Pack", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-07", "Position": 2, "Images": ""
    },

    # 8. Mini Heart Hand Candles (1 Piece, 2 Pieces, 3 Pieces)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-CND-08", "Name": "Mini Heart Hand Candles",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Cute heart-shaped candles in Rose, Ocean, or Lavender scents. Perfect for gifting.",
        "Description": "Small heart-shaped candles available in rose, ocean, and lavender fragrances — perfect for gifting and adding a decorative touch to your space.\n\n• Scents: Rose, Ocean, Lavender\n• Burn Time: 5 Hours (individual)\n• Pricing: 1 Piece (Rs. 80) / 2 Pieces (Rs. 150) / 3 Pieces (Rs. 250)",
        "In stock?": 1, "Stock": 100, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "Scented Candles", "Tags": "Heart, Mini, Gift, Rose, Lavender",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece, 2 Pieces, 3 Pieces", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose, Ocean, Lavender", "Attribute 2 visible": 1, "Attribute 2 global": 1,
        "Parent": "", "Position": 7, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-08-1", "Name": "Mini Heart Hand Candles - 1 Piece",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "1 Piece Mini Heart Candle",
        "In stock?": 1, "Stock": 30, "Allow customer reviews?": 1,
        "Regular price": "80", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "1 Piece", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-08", "Position": 1, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-08-2", "Name": "Mini Heart Hand Candles - 2 Pieces",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "2 Pieces Mini Heart Candles",
        "In stock?": 1, "Stock": 35, "Allow customer reviews?": 1,
        "Regular price": "150", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "2 Pieces", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-08", "Position": 2, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-08-3", "Name": "Mini Heart Hand Candles - 3 Pieces",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "3 Pieces Mini Heart Candles",
        "In stock?": 1, "Stock": 35, "Allow customer reviews?": 1,
        "Regular price": "250", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "3 Pieces", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "Fragrance", "Attribute 2 value(s)": "Rose", "Attribute 2 visible": "", "Attribute 2 global": 1,
        "Parent": "AUR-CND-08", "Position": 3, "Images": ""
    },

    # 9. Aurika Rose Heart Candle
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-09", "Name": "Aurika Rose Heart Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Elegant heart-shaped candle with rich English Rose fragrance. Burn time: 15 hours.",
        "Description": "An elegant heart-shaped candle with a beautiful rose fragrance, designed to add warmth, charm, and elegance to your space.\n\n• Fragrance: Romantic Rose\n• Burn Time: 15 Hours\n• Shape: Sculpted Rose Heart\n• Hand-poured craftsmanship",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "999", "Sale price": "", "Categories": "Scented Candles", "Tags": "Rose, Heart, Luxury, Romance",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 8, "Images": ""
    },

    # 10. Rose Flower Scented Candle
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-10", "Name": "Rose Flower Scented Candle",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Decorative rose-shaped candle with delicate rose aroma. Burn time: 5 hours.",
        "Description": "A decorative rose-shaped candle with a delicate rose fragrance, bringing a floral and elegant touch to your space.\n\n• Fragrance: Rose Petals\n• Burn Time: 5 Hours\n• Mold: Detailed 3D Rose Flower",
        "In stock?": 1, "Stock": 50, "Allow customer reviews?": 1,
        "Regular price": "299", "Sale price": "", "Categories": "Scented Candles", "Tags": "Rose, Flower, Decorative",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 9, "Images": ""
    },

    # 11. Star Fish Scented Candle
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-11", "Name": "Star Fish Scented Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Artistic starfish candle with refreshing ocean fragrance. Burn time: 8 hours.",
        "Description": "An artistic starfish-shaped candle with a refreshing ocean fragrance, inspired by the beauty of the sea.\n\n• Fragrance: Sea Mist & Ocean\n• Burn Time: 8 Hours\n• Shape: Coastal Starfish\n• Hand-poured with natural wax",
        "In stock?": 1, "Stock": 35, "Allow customer reviews?": 1,
        "Regular price": "1199", "Sale price": "", "Categories": "Scented Candles", "Tags": "Starfish, Ocean, Sea, Decorative",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 10, "Images": ""
    },

    # 12. DIY Art Kit - Paint & Create (4 Toys vs 8 Toys) - Variable
    {
        "ID": "", "Type": "variable", "SKU": "AUR-DIY-KIT", "Name": "Aurika DIY Art Kit — Paint & Create for Kids",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Screen-free hands-on painting kit for kids ages 3-12. Includes concrete toys, paints & brush.",
        "Description": "Turn screen time into creative time! Our hands-on DIY painting kit allows children to paint their own concrete figurines and display their artwork with pride.\n\n• Recommended Age: 3 to 12 Years\n• What's Included:\n  - Concrete Figurines (Kitty, Unicorn, Airplane, Car, Rainbow & more)\n  - Child-Safe Acrylic Painting Colors\n  - High-Quality Detail Painting Brush\n• Child-Safe, Non-Toxic & Washable Materials",
        "In stock?": 1, "Stock": 80, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "DIY Art Kits", "Tags": "DIY, Kids, Painting, Toys, Creative, Screen-Free",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + Paints + Brush), Large Kit (8 Toys + Paints + Brush)", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 11, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-KIT-4", "Name": "Aurika DIY Art Kit — Small Kit (4 Toys)",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes 4 concrete toys, paints, and 1 painting brush (Ages 3-12).",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "299", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + Paints + Brush)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-KIT", "Position": 1, "Images": ""
    },
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-KIT-8", "Name": "Aurika DIY Art Kit — Large Kit (8 Toys)",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes 8 concrete toys, vibrant paints, and painting brush (Ages 3-12).",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "599", "Sale price": "", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Large Kit (8 Toys + Paints + Brush)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-KIT", "Position": 2, "Images": ""
    }
]

with open(csv_path, mode="w", newline="", encoding="utf-8-sig") as f:
    writer = csv.DictWriter(f, fieldnames=headers)
    writer.writeheader()
    for row in rows:
        writer.writerow(row)

print(f"Client Products CSV generated: {csv_path} with {len(rows)} rows")

# 2. Build One-Click Auto-Populate Plugin that creates all these products directly in WooCommerce with default images!
auto_plugin_dir = os.path.join(output_dir, "_auto_products_plugin_temp")
if os.path.exists(auto_plugin_dir):
    shutil.rmtree(auto_plugin_dir)

plugin_folder = os.path.join(auto_plugin_dir, "aurika-auto-products-importer")
os.makedirs(os.path.join(plugin_folder, "assets"), exist_ok=True)

# Copy hero-aurika.jpg as default placeholder image for auto-attaching
hero_src = os.path.join(r"C:\Users\DC\BIC data\Aurika\aurika-theme\assets\images\hero-aurika.jpg")
shutil.copy2(hero_src, os.path.join(plugin_folder, "assets", "product-placeholder.jpg"))

plugin_code = """<?php
/**
 * Plugin Name: Aurika One-Click Product & Image Auto-Populator
 * Plugin URI: https://aurika.online
 * Description: 1-Click tool that creates all official Aurika client products (exact titles, prices, descriptions, and variations) and automatically assigns photos.
 * Version: 1.0.0
 * Author: Aurika Team
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Auto-Import Aurika Products',
        '⚡ Import Client Products',
        'manage_options',
        'aurika-auto-products',
        'aurika_render_auto_products_page'
    );
} );

function aurika_render_auto_products_page() {
    if ( isset( $_POST['aurika_do_import'] ) && check_admin_referer( 'aurika_import_nonce' ) ) {
        $result = aurika_execute_product_creation();
        echo '<div class="notice notice-success is-dismissible" style="padding:15px; font-size:15px;"><p><strong>🎉 ' . esc_html( $result ) . '</strong></p></div>';
    }
    ?>
    <div class="wrap" style="max-width:850px; background:#fff; padding:30px; border-radius:12px; margin-top:20px; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
        <h1 style="font-family:serif; font-size:28px; color:#111; margin-bottom:10px;">⚡ Aurika 1-Click Product &amp; Image Auto-Importer</h1>
        <p style="font-size:15px; color:#555; line-height:1.7;">
            This tool automatically populates all <strong>12 official Aurika products</strong> with exact client data:
            prices (Rs. 80 to Rs. 1,499), variations (1 Piece, 2 Pieces, 3 Pieces, Small Kit 4 Toys, Large Kit 8 Toys), burn times, fragrances, categories, and assigns product images automatically!
        </p>
        <hr style="margin:25px 0; border:0; border-top:1px solid #eee;">
        <form method="post">
            <?php wp_nonce_field( 'aurika_import_nonce' ); ?>
            <button type="submit" name="aurika_do_import" class="button button-primary button-hero" style="background:#b8860b; border-color:#b8860b; font-weight:700; font-size:15px; padding:12px 30px; height:auto; text-transform:uppercase; letter-spacing:1px;">
                🚀 Create / Update All 12 Products Now
            </button>
        </form>
    </div>
    <?php
}

function aurika_execute_product_creation() {
    if ( ! class_exists( 'WC_Product_Simple' ) ) {
        return 'WooCommerce is required.';
    }

    // Attach placeholder image to media library if not attached
    $img_path = plugin_dir_path( __FILE__ ) . 'assets/product-placeholder.jpg';
    $attachment_id = 0;
    if ( file_exists( $img_path ) ) {
        $upload_dir = wp_upload_dir();
        $target = $upload_dir['path'] . '/aurika-product-featured.jpg';
        if ( ! file_exists( $target ) ) {
            copy( $img_path, $target );
        }
        $file_type = wp_check_filetype( basename( $target ), null );
        $attachment = array(
            'guid'           => $upload_dir['url'] . '/' . basename( $target ),
            'post_mime_type' => $file_type['type'],
            'post_title'     => 'Aurika Product Image',
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
        $attachment_id = wp_insert_attachment( $attachment, $target );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attachment_id, $target );
        wp_update_attachment_metadata( $attachment_id, $attach_data );
    }

    // Category terms
    $cat_cnd = wp_create_term( 'Scented Candles', 'product_cat' );
    $cat_diy = wp_create_term( 'DIY Art Kits', 'product_cat' );
    $cat_cnd_id = is_array($cat_cnd) ? $cat_cnd['term_id'] : (int) get_term_by('name', 'Scented Candles', 'product_cat')->term_id;
    $cat_diy_id = is_array($cat_diy) ? $cat_diy['term_id'] : (int) get_term_by('name', 'DIY Art Kits', 'product_cat')->term_id;

    $products_data = array(
        array(
            'sku' => 'AUR-CND-01', 'name' => 'Lavender Scented Candle', 'price' => '999', 'cat' => array($cat_cnd_id),
            'short' => 'Fragrance: Lavender | Burning Time: 24 Hours | Wax: Natural Paraffin Blend',
            'desc' => "A beautifully crafted scented candle with a soothing lavender fragrance, designed to add warmth and elegance to your space.\n\n• Fragrance: French Lavender\n• Burn Time: 24 Hours\n• Wax: Paraffin Blend\n• Variants: Rose, Sunflower, Lavender"
        ),
        array(
            'sku' => 'AUR-CND-02', 'name' => 'Sea Shell Ocean Candle (Artistic Edition)', 'price' => '699', 'cat' => array($cat_cnd_id),
            'short' => 'Fragrance: Ocean | Burning Time: 10 Hours | Hand-carved Sea Shell Design',
            'desc' => "An artistic sea-shell inspired candle with a refreshing ocean fragrance, designed to combine decorative coastal appeal with a fragrant sensory experience.\n\n• Fragrance: Ocean\n• Burn Time: 10 Hours"
        ),
        array(
            'sku' => 'AUR-CND-04', 'name' => 'Sea Shell Ocean Candle Set (2 Pieces with Tray)', 'price' => '1499', 'cat' => array($cat_cnd_id),
            'short' => 'Includes 2 Sea Shell Candles + Luxury Decorative Tray | Fragrance: Ocean',
            'desc' => "A two-piece sea-shell candle set with a decorative tray and refreshing ocean fragrance, perfect for gifting or elegant home décor.\n\n• 2 Candles + 1 Tray\n• Fragrance: Ocean\n• Burn Time: 10 Hours each"
        ),
        array(
            'sku' => 'AUR-CND-05', 'name' => 'Sea Shell Ocean Candle (Set of 2 Pieces)', 'price' => '1299', 'cat' => array($cat_cnd_id),
            'short' => 'Set of 2 Artistic Sea Shell Candles | Fragrance: Ocean | Burn Time: 10 Hours Each',
            'desc' => "A set of two artistic sea-shell candles with a refreshing ocean fragrance, adding charm and warmth to your space.\n\n• Quantity: 2 Pieces\n• Fragrance: Ocean"
        ),
        array(
            'sku' => 'AUR-CND-09', 'name' => 'Aurika Rose Heart Candle', 'price' => '999', 'cat' => array($cat_cnd_id),
            'short' => 'Elegant heart-shaped candle with rich English Rose fragrance. Burn time: 15 hours.',
            'desc' => "An elegant heart-shaped candle with a beautiful rose fragrance, designed to add warmth, charm, and elegance to your space.\n\n• Fragrance: Rose\n• Burn Time: 15 Hours"
        ),
        array(
            'sku' => 'AUR-CND-10', 'name' => 'Rose Flower Scented Candle', 'price' => '299', 'cat' => array($cat_cnd_id),
            'short' => 'Decorative rose-shaped candle with delicate rose aroma. Burn time: 5 hours.',
            'desc' => "A decorative rose-shaped candle with a delicate rose fragrance, bringing a floral and elegant touch to your space.\n\n• Fragrance: Rose\n• Burn Time: 5 Hours"
        ),
        array(
            'sku' => 'AUR-CND-11', 'name' => 'Star Fish Scented Candle', 'price' => '1199', 'cat' => array($cat_cnd_id),
            'short' => 'Artistic starfish candle with refreshing ocean fragrance. Burn time: 8 hours.',
            'desc' => "An artistic starfish-shaped candle with a refreshing ocean fragrance, inspired by the beauty of the sea.\n\n• Fragrance: Ocean\n• Burn Time: 8 Hours"
        ),
        array(
            'sku' => 'AUR-DIY-08', 'name' => 'Aurika DIY Art Kit (8 Concrete Toys Set)', 'price' => '599', 'cat' => array($cat_diy_id),
            'short' => '8 Concrete Toys + Acrylic Paints + Brush | Ages 3-12 | Screen-Free Play',
            'desc' => "A creative DIY painting kit with 8 concrete toys, paints, and a brush, designed to encourage hands-on creativity and imaginative play for children ages 3–12."
        ),
        array(
            'sku' => 'AUR-DIY-04', 'name' => 'Aurika DIY Art Kit (4 Concrete Toys Set)', 'price' => '299', 'cat' => array($cat_diy_id),
            'short' => '4 Concrete Toys + Acrylic Paints + Brush | Ages 3-12 | Compact Play Kit',
            'desc' => "A compact DIY painting kit with 4 concrete toys, paints, and a brush, designed for creative, hands-on fun for children ages 3–12."
        )
    );

    $count = 0;
    foreach ( $products_data as $p ) {
        $existing_id = wc_get_product_id_by_sku( $p['sku'] );
        $product = $existing_id ? wc_get_product( $existing_id ) : new WC_Product_Simple();
        $product->set_name( $p['name'] );
        $product->set_sku( $p['sku'] );
        $product->set_regular_price( $p['price'] );
        $product->set_short_description( $p['short'] );
        $product->set_description( $p['desc'] );
        $product->set_category_ids( $p['cat'] );
        $product->set_status( 'publish' );
        $product->set_manage_stock( false );
        $product->set_stock_status( 'instock' );
        if ( $attachment_id && ! $product->get_image_id() ) {
            $product->set_image_id( $attachment_id );
        }
        $product->save();
        $count++;
    }

    // Now create Variable Product: Peony Flower Scented Candle (1 pc Rs. 199 / 3 pcs Rs. 499)
    $peony_sku = 'AUR-CND-06';
    $peony_id = wc_get_product_id_by_sku( $peony_sku );
    $peony = $peony_id ? wc_get_product( $peony_id ) : new WC_Product_Variable();
    $peony->set_name( 'Peony Flower Scented Candle' );
    $peony->set_sku( $peony_sku );
    $peony->set_short_description( 'Delicate flower-shaped candle. Fragrance: Lavender, Rose, Lily | 1 Piece (Rs. 199) or 3 Pieces (Rs. 499)' );
    $peony->set_description( "A delicate peony flower-shaped candle available in lavender, rose, and lily fragrances, perfect for adding a floral touch to your space.\n\n• Burn Time: 6 Hours each\n• 1 Piece: Rs. 199\n• 3 Pieces Pack: Rs. 499" );
    $peony->set_category_ids( array( $cat_cnd_id ) );
    $peony->set_status( 'publish' );
    if ( $attachment_id && ! $peony->get_image_id() ) {
        $peony->set_image_id( $attachment_id );
    }

    // Attribute
    $attr = new WC_Product_Attribute();
    $attr->set_name( 'Pack Size' );
    $attr->set_options( array( '1 Piece', '3 Pieces Pack' ) );
    $attr->set_position( 0 );
    $attr->set_visible( true );
    $attr->set_variation( true );
    $peony->set_attributes( array( $attr ) );
    $saved_peony_id = $peony->save();

    // Create variations
    $v1 = new WC_Product_Variation();
    $v1->set_parent_id( $saved_peony_id );
    $v1->set_attributes( array( 'pack-size' => '1 Piece' ) );
    $v1->set_regular_price( '199' );
    $v1->set_status( 'publish' );
    $v1->set_stock_status( 'instock' );
    $v1->save();

    $v2 = new WC_Product_Variation();
    $v2->set_parent_id( $saved_peony_id );
    $v2->set_attributes( array( 'pack-size' => '3 Pieces Pack' ) );
    $v2->set_regular_price( '499' );
    $v2->set_status( 'publish' );
    $v2->set_stock_status( 'instock' );
    $v2->save();
    $count++;

    // Daisy Flower Candle (1 pc Rs. 150 / 3 pcs Rs. 399)
    $daisy_sku = 'AUR-CND-07';
    $daisy_id = wc_get_product_id_by_sku( $daisy_sku );
    $daisy = $daisy_id ? wc_get_product( $daisy_id ) : new WC_Product_Variable();
    $daisy->set_name( 'Daisy Flower Candle' );
    $daisy->set_sku( $daisy_sku );
    $daisy->set_short_description( 'Charming daisy candle in Rose, Lily, Lavender, Sunflower scents. 1 pc (Rs. 150) / 3 pcs (Rs. 399)' );
    $daisy->set_description( "A charming daisy-shaped candle available in rose, lily, lavender, and sunflower fragrances.\n\n• Burn Time: 5 Hours\n• 1 Piece: Rs. 150\n• 3 Pieces Pack: Rs. 399" );
    $daisy->set_category_ids( array( $cat_cnd_id ) );
    $daisy->set_status( 'publish' );
    if ( $attachment_id && ! $daisy->get_image_id() ) {
        $daisy->set_image_id( $attachment_id );
    }
    $attr_d = new WC_Product_Attribute();
    $attr_d->set_name( 'Pack Size' );
    $attr_d->set_options( array( '1 Piece', '3 Pieces Pack' ) );
    $attr_d->set_position( 0 );
    $attr_d->set_visible( true );
    $attr_d->set_variation( true );
    $daisy->set_attributes( array( $attr_d ) );
    $saved_daisy_id = $daisy->save();

    $vd1 = new WC_Product_Variation();
    $vd1->set_parent_id( $saved_daisy_id );
    $vd1->set_attributes( array( 'pack-size' => '1 Piece' ) );
    $vd1->set_regular_price( '150' );
    $vd1->set_status( 'publish' );
    $vd1->set_stock_status( 'instock' );
    $vd1->save();

    $vd2 = new WC_Product_Variation();
    $vd2->set_parent_id( $saved_daisy_id );
    $vd2->set_attributes( array( 'pack-size' => '3 Pieces Pack' ) );
    $vd2->set_regular_price( '399' );
    $vd2->set_status( 'publish' );
    $vd2->set_stock_status( 'instock' );
    $vd2->save();
    $count++;

    // Mini Heart Hand Candles (1 pc Rs. 80 / 2 pcs Rs. 150 / 3 pcs Rs. 250)
    $heart_sku = 'AUR-CND-08';
    $heart_id = wc_get_product_id_by_sku( $heart_sku );
    $heart = $heart_id ? wc_get_product( $heart_id ) : new WC_Product_Variable();
    $heart->set_name( 'Mini Heart Hand Candles' );
    $heart->set_sku( $heart_sku );
    $heart->set_short_description( 'Mini heart candles in Rose, Ocean, Lavender. 1 pc (Rs. 80) / 2 pcs (Rs. 150) / 3 pcs (Rs. 250)' );
    $heart->set_description( "Small heart-shaped candles available in rose, ocean, and lavender fragrances — perfect for gifting and home decor.\n\n• Burn Time: 5 Hours\n• 1 Piece: Rs. 80\n• 2 Pieces: Rs. 150\n• 3 Pieces: Rs. 250" );
    $heart->set_category_ids( array( $cat_cnd_id ) );
    $heart->set_status( 'publish' );
    if ( $attachment_id && ! $heart->get_image_id() ) {
        $heart->set_image_id( $attachment_id );
    }
    $attr_h = new WC_Product_Attribute();
    $attr_h->set_name( 'Pack Size' );
    $attr_h->set_options( array( '1 Piece', '2 Pieces', '3 Pieces' ) );
    $attr_h->set_position( 0 );
    $attr_h->set_visible( true );
    $attr_h->set_variation( true );
    $heart->set_attributes( array( $attr_h ) );
    $saved_heart_id = $heart->save();

    $vh1 = new WC_Product_Variation();
    $vh1->set_parent_id( $saved_heart_id );
    $vh1->set_attributes( array( 'pack-size' => '1 Piece' ) );
    $vh1->set_regular_price( '80' );
    $vh1->set_status( 'publish' );
    $vh1->set_stock_status( 'instock' );
    $vh1->save();

    $vh2 = new WC_Product_Variation();
    $vh2->set_parent_id( $saved_heart_id );
    $vh2->set_attributes( array( 'pack-size' => '2 Pieces' ) );
    $vh2->set_regular_price( '150' );
    $vh2->set_status( 'publish' );
    $vh2->set_stock_status( 'instock' );
    $vh2->save();

    $vh3 = new WC_Product_Variation();
    $vh3->set_parent_id( $saved_heart_id );
    $vh3->set_attributes( array( 'pack-size' => '3 Pieces' ) );
    $vh3->set_regular_price( '250' );
    $vh3->set_status( 'publish' );
    $vh3->set_stock_status( 'instock' );
    $vh3->save();
    $count++;

    return "Successfully created/updated {$count} products with client pricing, descriptions, variations, and images!";
}
"""

with open(os.path.join(plugin_folder, "aurika-auto-products-importer.php"), "w", encoding="utf-8") as f:
    f.write(plugin_code)

plugin_zip = os.path.join(output_dir, "aurika-auto-products-importer-plugin.zip")
if os.path.exists(plugin_zip):
    os.remove(plugin_zip)

with zipfile.ZipFile(plugin_zip, 'w', zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(auto_plugin_dir):
        for file in files:
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, auto_plugin_dir).replace('\\', '/')
            zf.write(full_path, rel_path)

shutil.rmtree(auto_plugin_dir)
print(f"Auto-Importer Plugin generated: {plugin_zip} ({os.path.getsize(plugin_zip)/1024/1024:.2f} MB)")
