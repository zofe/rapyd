<?php

namespace Zofe\Rapyd\Mechanisms;

use Illuminate\View\Compilers\ComponentTagCompiler;

class RapydTagPrecompiler extends ComponentTagCompiler
{
    public function __invoke($value, $params=[])
    {
        $linkpattern = '/<a\s+data-ref="link-view"\s+href="#">(.*?)<\/a>/';
        $value = $this->replaceLinkViewTags($value, $linkpattern, $params);

        $linkpattern = '/<a\s+data-ref="link-edit"\s+href="#">(.*?)<\/a>/';
        $value = $this->replaceLinkEditTags($value, $linkpattern, $params);

        return $value;
    }

    protected function replaceLinkViewTags($value, $pattern, $params=[])
    {
        return preg_replace_callback($pattern, function (array $matches) use ($params) {
            $content = $matches[1];
            $viewId = str_replace(['{{', '}}'], '', $content);
            if(count($params) && isset($params['route'])) {

                return '<a href="{{ route(\'' . $params['route'] . '\','.$viewId.') }}">' . $content . '</a>';
            }
            return $matches[0];
        }, $value);
    }

    protected function replaceLinkEditTags($value, $pattern, $params=[])
    {
        return preg_replace_callback($pattern, function (array $matches) use ($params) {
            $content = $matches[1];
            $viewId = str_replace(['{{', '}}'], '', $content);
            if(count($params) && isset($params['route'])) {
                return '<a href="{{ route(\'' . $params['route'] . '\','.$viewId.') }}" class="btn btn-outline-primary">Edit</a>';
            }
            return $matches[0];
        }, $value);
    }
}