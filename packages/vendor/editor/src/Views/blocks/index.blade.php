<!-- resources/views/blocks/index.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Blocs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #3b82f6;
        }
        .blocks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .block-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .block-card:hover {
            transform: translateY(-5px);
        }
        .block-header {
            padding: 20px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .block-content {
            padding: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-right: 8px;
        }
        .badge-primary { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-cubes"></i> Gestion des Blocs</h1>
            <a href="/editor" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Retour à l'éditeur
            </a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_blocks'] }}</div>
                <div class="stat-label">Total Blocks</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_usage'] }}</div>
                <div class="stat-label">Total Uses</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_views'] }}</div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>
        
        <div class="blocks-grid">
            @foreach($blocks as $block)
            <div class="block-card">
                <div class="block-header">
                    <h3 style="margin: 0;">
                        <i class="fas {{ $block->icon }}"></i> {{ $block->name }}
                    </h3>
                    <div style="margin-top: 10px;">
                        <span class="badge badge-primary">{{ $block->category }}</span>
                        <span class="badge badge-success">{{ $block->website_type }}</span>
                    </div>
                </div>
                <div class="block-content">
                    <p style="color: #6b7280; margin-bottom: 15px;">
                        {{ Str::limit($block->description, 100) }}
                    </p>
                    <div style="display: flex; justify-content: space-between; color: #9ca3af; font-size: 0.9rem;">
                        <span><i class="fas fa-download"></i> {{ $block->usage_count }} uses</span>
                        <span><i class="fas fa-eye"></i> {{ $block->views_count }} views</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($blocks instanceof \Illuminate\Pagination\LengthAwarePaginator && $blocks->hasPages())
        <div style="margin-top: 40px; text-align: center;">
            {{ $blocks->links() }}
        </div>
        @endif
    </div>
</body>
</html>