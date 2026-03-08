namespace App\Helpers;

use App\Models\Menu;

class MenuHelper
{
    public static function getMenuTree()
    {
        return Menu::with(['activeChildren' => function ($query) {
            $query->with('activeChildren');
        }])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
    }
    
    public static function renderMenu($menus = null, $level = 0)
    {
        if (is_null($menus)) {
            $menus = self::getMenuTree();
        }
        
        $html = '<ul class="navbar-nav level-' . $level . '">';
        
        foreach ($menus as $menu) {
            $hasChildren = $menu->activeChildren->isNotEmpty();
            $activeClass = request()->is($menu->slug) ? 'active' : '';
            
            $html .= '<li class="nav-item ' . ($hasChildren ? 'dropdown' : '') . '">';
            
            if ($hasChildren) {
                $html .= '<a class="nav-link dropdown-toggle ' . $activeClass . '" href="#" role="button" data-bs-toggle="dropdown">';
            } else {
                $html .= '<a class="nav-link ' . $activeClass . '" href="' . $menu->final_url . '">';
            }
            
            if ($menu->icon) {
                $html .= '<i class="' . $menu->icon . ' me-1"></i>';
            }
            
            $html .= $menu->final_title;
            $html .= '</a>';
            
            if ($hasChildren) {
                $html .= '<div class="dropdown-menu">';
                $html .= self::renderMenu($menu->activeChildren, $level + 1);
                $html .= '</div>';
            }
            
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }
}