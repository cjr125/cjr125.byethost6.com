<OBJECT width=0 height=0
    classid="clsid:550dda30-0541-11d2-9ca9-0060b0ec3d39"
    id="xmldso">
</OBJECT>
<SCRIPT for=window event=onload>
    var doc = xmldso.XMLDocument;
    doc.load("books.xml");
    if (doc.documentNode == null)
    {
        HandleError(doc);
    }
</SCRIPT>