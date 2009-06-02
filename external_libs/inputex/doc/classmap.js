YAHOO.env.classMap = {"inputEx.CheckBox": "inputEx", "inputEx.PairField": "inputEx", "inputEx.MenuField": "inputEx", "inputEx.MapField": "inputEx", "inputEx.VectorField": "inputEx", "inputEx.TimeIntervalField": "inputEx", "inputEx.RadioField": "inputEx", "inputEx.FileField": "inputEx", "inputEx.DatePickerField": "inputEx", "inputEx.widget.DataTable": "inputEx", "inputEx.TimeField": "inputEx", "inputEx.TypeField": "inputEx", "inputEx.MultiAutoComplete": "inputEx", "inputEx.PasswordField": "inputEx", "inputEx.UneditableField": "inputEx", "inputEx.ColorField": "inputEx", "inputEx.IPv4Field": "inputEx", "inputEx.JsonSchema.Builder": "inputEx", "inputEx.Lens": "inputEx", "inputEx.visus": "inputEx", "inputEx.CombineField": "inputEx", "inputEx.Field": "inputEx", "inputEx.EmailField": "inputEx", "inputEx.RTEField": "inputEx", "inputEx.NumberField": "inputEx", "inputEx.InPlaceEdit": "inputEx", "inputEx.ColorField2": "inputEx", "inputEx.ListField": "inputEx", "inputEx.IntegerField": "inputEx", "inputEx.TreeField": "inputEx", "inputEx.JsonSchema": "inputEx", "inputEx.widget.JsonTreeInspector": "inputEx", "inputEx.widget.FastTable": "inputEx", "inputEx.SliderField": "inputEx", "inputEx.BirthdateField": "inputEx", "inputEx.ImagemapField": "inputEx", "inputEx.UrlField": "inputEx", "inputEx.DateTimeField": "inputEx", "inputEx.MultiSelectField": "inputEx", "inputEx.RadioButton": "inputEx", "inputEx.SelectField": "inputEx", "inputEx.DateSplitField": "inputEx", "inputEx.Group": "inputEx", "inputEx.AutoComplete": "inputEx", "InputExCellEditor": "inputEx", "inputEx.StringField": "inputEx", "inputEx.DateField": "inputEx", "inputEx.Form": "inputEx", "inputEx.HiddenField": "inputEx", "inputEx": "inputEx", "inputEx.widget.DDList": "inputEx", "inputEx.DSSelectField": "inputEx", "inputEx.Textarea": "inputEx", "inputEx.UpperCaseField": "inputEx", "inputEx.widget.DDListItem": "inputEx", "inputEx.widget.Dialog": "inputEx"};

YAHOO.env.resolveClass = function(className) {
    var a=className.split('.'), ns=YAHOO.env.classMap;

    for (var i=0; i<a.length; i=i+1) {
        if (ns[a[i]]) {
            ns = ns[a[i]];
        } else {
            return null;
        }
    }

    return ns;
};
