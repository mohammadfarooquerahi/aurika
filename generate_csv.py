import csv
import os

csv_path = r"C:\Users\DC\BIC data\Aurika\aurika-products-bulk-import.csv"

# Columns matching WooCommerce standard product CSV importer
headers = [
    "ID", "Type", "SKU", "Name", "Published", "Is featured?", "Visibility in catalog",
    "Short description", "Description", "In stock?", "Stock", "Allow customer reviews?",
    "Regular price", "Sale price", "Categories", "Tags",
    "Attribute 1 name", "Attribute 1 value(s)", "Attribute 1 visible", "Attribute 1 global",
    "Attribute 2 name", "Attribute 2 value(s)", "Attribute 2 visible", "Attribute 2 global",
    "Parent", "Position"
]

rows = [
    # 1. Lavender Bliss (Simple Product)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-LAV", "Name": "Lavender Bliss Scented Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Hand-poured lavender scented candle with long-lasting calming aroma. 100% natural soy wax & lead-free wick.",
        "Description": "Infuse your home with the soothing, therapeutic fragrance of French Lavender. Each candle is individually hand-poured in Pakistan with pure essential fragrance oils. Clean, non-toxic burn for up to 40 hours.",
        "In stock?": 1, "Stock": 50, "Allow customer reviews?": 1,
        "Regular price": "1499", "Sale price": "", "Categories": "Scented Candles", "Tags": "Candles, Lavender, Handmade, Aromatherapy",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 0
    },

    # 2. Vanilla Dreams (Simple Product)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-VAN", "Name": "Vanilla Dreams Scented Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Warm, rich Bourbon vanilla candle that fills any room with cozy sweet comfort.",
        "Description": "Experience the warmth of freshly baked vanilla pods blended with subtle notes of warm amber. Hand-poured with natural wax and long-lasting scent throw.",
        "In stock?": 1, "Stock": 50, "Allow customer reviews?": 1,
        "Regular price": "1499", "Sale price": "", "Categories": "Scented Candles", "Tags": "Candles, Vanilla, Cozy, Home Decor",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 1
    },

    # 3. Rose Garden (Simple Product)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-ROS", "Name": "Rose Garden Scented Candle",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Delicate and romantic fresh English rose petals aroma for a fresh floral ambience.",
        "Description": "A bouquet of fresh roses in a candle. Hand-poured with premium botanical wax and delicate floral fragrances.",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "1599", "Sale price": "", "Categories": "Scented Candles", "Tags": "Candles, Rose, Floral, Romantic",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 2
    },

    # 4. Jasmine Night (Simple Product)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-JAS", "Name": "Jasmine Night Scented Candle",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Exotic, calming night-blooming Motia / Jasmine fragrance hand-crafted with love.",
        "Description": "Authentic Pakistani Motia and Jasmine blossoms captured in an elegant hand-poured candle. Long-lasting scent throw and clean burn.",
        "In stock?": 1, "Stock": 45, "Allow customer reviews?": 1,
        "Regular price": "1599", "Sale price": "", "Categories": "Scented Candles", "Tags": "Candles, Jasmine, Motia, Traditional",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 3
    },

    # 5. Ocean Breeze (Simple Product)
    {
        "ID": "", "Type": "simple", "SKU": "AUR-CND-OCN", "Name": "Ocean Breeze Scented Candle",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Crisp, refreshing aquatic scent with notes of sea salt, marine breeze, and zesty citrus.",
        "Description": "Bring the refreshing freshness of the ocean into your living space. Hand-poured with non-toxic wax and lead-free wick.",
        "In stock?": 1, "Stock": 35, "Allow customer reviews?": 1,
        "Regular price": "1499", "Sale price": "", "Categories": "Scented Candles", "Tags": "Candles, Ocean, Fresh, Summer",
        "Attribute 1 name": "", "Attribute 1 value(s)": "", "Attribute 1 visible": "", "Attribute 1 global": "",
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 4
    },

    # 6. Luxury Candle Gift Set (Variable Product: Pack Size)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-CND-SET", "Name": "Aurika Luxury Candle Gift Set",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Curated collection of our finest hand-poured scented candles in a luxury gold-embossed gift box.",
        "Description": "The perfect gift for weddings, housewarmings, or festive celebrations. Choose between our 2-piece duet or 4-piece luxury collection.",
        "In stock?": 1, "Stock": 30, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "Scented Candles", "Tags": "Gift Sets, Candles, Luxury, Festive",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "2-Piece Gift Set, 4-Piece Luxury Set", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 5
    },
    # 6a. Variation: 2-Piece
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-SET-2", "Name": "Aurika Luxury Candle Gift Set - 2-Piece Gift Set",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes Lavender Bliss and Vanilla Dreams (2 candles + premium gift box).",
        "In stock?": 1, "Stock": 15, "Allow customer reviews?": 1,
        "Regular price": "2899", "Sale price": "2699", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "2-Piece Gift Set", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-CND-SET", "Position": 1
    },
    # 6b. Variation: 4-Piece
    {
        "ID": "", "Type": "variation", "SKU": "AUR-CND-SET-4", "Name": "Aurika Luxury Candle Gift Set - 4-Piece Luxury Set",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes Lavender, Vanilla, Rose, and Jasmine (4 candles + luxury gold gift box).",
        "In stock?": 1, "Stock": 15, "Allow customer reviews?": 1,
        "Regular price": "5499", "Sale price": "4999", "Categories": "", "Tags": "",
        "Attribute 1 name": "Pack Size", "Attribute 1 value(s)": "4-Piece Luxury Set", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-CND-SET", "Position": 2
    },

    # 7. DIY Art Kit - Animals Edition (Variable: Size)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-DIY-ANM", "Name": "Aurika DIY Art Kit — Animals & Shapes",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Screen-free creative kit! Paint concrete animal figurines (Kitty, Unicorn, Bear & more). Child-safe paints included.",
        "Description": "Turn screen time into creative time! Includes hand-cast concrete animal toys, non-toxic vibrant acrylic paints, brush, and easy guide. Perfect for kids ages 3-12.",
        "In stock?": 1, "Stock": 60, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "DIY Art Kits", "Tags": "DIY, Art Kits, Kids, Screen-Free, Creativity",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + 6 Paints), Large Kit (8 Toys + 12 Paints)", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 6
    },
    # 7a. Variation: Small Kit
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-ANM-SM", "Name": "Aurika DIY Art Kit — Animals & Shapes - Small Kit",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes 4 concrete animal shapes, 6 acrylic colors, and 1 detail brush.",
        "In stock?": 1, "Stock": 30, "Allow customer reviews?": 1,
        "Regular price": "1299", "Sale price": "1199", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + 6 Paints)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-ANM", "Position": 1
    },
    # 7b. Variation: Large Kit
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-ANM-LG", "Name": "Aurika DIY Art Kit — Animals & Shapes - Large Kit",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes 8 concrete animal shapes, 12 vibrant colors, 2 brushes, and color mixing palette.",
        "In stock?": 1, "Stock": 30, "Allow customer reviews?": 1,
        "Regular price": "2199", "Sale price": "1999", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Large Kit (8 Toys + 12 Paints)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-ANM", "Position": 2
    },

    # 8. DIY Art Kit - Vehicles & Adventure (Variable: Size)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-DIY-VEH", "Name": "Aurika DIY Art Kit — Vehicles & Aircraft",
        "Published": 1, "Is featured?": 1, "Visibility in catalog": "visible",
        "Short description": "Exciting paint kit featuring Planes, Cars, Trucks & Rockets. Includes child-safe paints and brush.",
        "Description": "Build, paint, and explore! Kids love painting their own airplanes, sports cars, and construction trucks. Child-safe, washable, and non-toxic materials.",
        "In stock?": 1, "Stock": 50, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "DIY Art Kits", "Tags": "DIY, Kids, Vehicles, Planes, Cars",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + 6 Paints), Large Kit (8 Toys + 12 Paints)", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 7
    },
    # 8a. Variation: Small Kit
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-VEH-SM", "Name": "Aurika DIY Art Kit — Vehicles & Aircraft - Small Kit",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes 4 vehicle shapes, 6 acrylic colors, and 1 brush.",
        "In stock?": 1, "Stock": 25, "Allow customer reviews?": 1,
        "Regular price": "1299", "Sale price": "1199", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + 6 Paints)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-VEH", "Position": 1
    },
    # 8b. Variation: Large Kit
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-VEH-LG", "Name": "Aurika DIY Art Kit — Vehicles & Aircraft - Large Kit",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "Includes 8 vehicle shapes, 12 paints, 2 brushes, and palette.",
        "In stock?": 1, "Stock": 25, "Allow customer reviews?": 1,
        "Regular price": "2199", "Sale price": "1999", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Large Kit (8 Toys + 12 Paints)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-VEH", "Position": 2
    },

    # 9. DIY Art Kit - Nature & Flowers (Variable: Size)
    {
        "ID": "", "Type": "variable", "SKU": "AUR-DIY-NAT", "Name": "Aurika DIY Art Kit — Nature & Floral",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "Paint butterflies, flowers, sunflowers & rainbow concrete shapes with cheerful paints.",
        "Description": "Creative floral art kit for young artists. Includes concrete flowers, butterflies, suns, paints, and brush.",
        "In stock?": 1, "Stock": 40, "Allow customer reviews?": 1,
        "Regular price": "", "Sale price": "", "Categories": "DIY Art Kits", "Tags": "DIY, Flowers, Nature, Rainbow, Kids",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + 6 Paints), Large Kit (8 Toys + 12 Paints)", "Attribute 1 visible": 1, "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "", "Position": 8
    },
    # 9a. Variation: Small
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-NAT-SM", "Name": "Aurika DIY Art Kit — Nature & Floral - Small Kit",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "4 nature shapes + 6 paints + brush.",
        "In stock?": 1, "Stock": 20, "Allow customer reviews?": 1,
        "Regular price": "1299", "Sale price": "1199", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Small Kit (4 Toys + 6 Paints)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-NAT", "Position": 1
    },
    # 9b. Variation: Large
    {
        "ID": "", "Type": "variation", "SKU": "AUR-DIY-NAT-LG", "Name": "Aurika DIY Art Kit — Nature & Floral - Large Kit",
        "Published": 1, "Is featured?": 0, "Visibility in catalog": "visible",
        "Short description": "", "Description": "8 nature shapes + 12 paints + 2 brushes + palette.",
        "In stock?": 1, "Stock": 20, "Allow customer reviews?": 1,
        "Regular price": "2199", "Sale price": "1999", "Categories": "", "Tags": "",
        "Attribute 1 name": "Kit Size", "Attribute 1 value(s)": "Large Kit (8 Toys + 12 Paints)", "Attribute 1 visible": "", "Attribute 1 global": 1,
        "Attribute 2 name": "", "Attribute 2 value(s)": "", "Attribute 2 visible": "", "Attribute 2 global": "",
        "Parent": "AUR-DIY-NAT", "Position": 2
    }
]

with open(csv_path, mode="w", newline="", encoding="utf-8-sig") as f:
    writer = csv.DictWriter(f, fieldnames=headers)
    writer.writeheader()
    for row in rows:
        writer.writerow(row)

print(f"CSV generated: {csv_path} with {len(rows)} rows ({len(headers)} columns)")
