<?php
namespace Type\Xml;

/**
 * Monkeypatching \Symfony\Component\CssSelector\CssSelector
 * to support a default namespace for css selectors
 *
 * @author jk
 * @codeCoverageIgnore
 */
class CssSelectorParser extends \Symfony\Component\CssSelector\CssSelector {

    /**
     * Translates a CSS expression to its XPath equivalent.
     * Optionally, a prefix can be added to the resulting XPath
     * expression with the $prefix parameter.
     *
     * @param  mixed  $cssExpr The CSS expression.
     * @param  string $prefix  An optional prefix for the XPath expression.
     *
     * @return string
     *
     * @throws ParseException When got None for xpath expression
     *
     * @api
     */
    static public function cssToXPath($cssExpr, $prefix = 'descendant-or-self::', $defaultNamespace = '*') {
        return self::toXPath($cssExpr, $prefix);
    }
}
?>
