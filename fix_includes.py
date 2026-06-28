from pathlib import Path
import re
root = Path('.').resolve()
pattern = re.compile(r'^(\s*)(require_once|require|include_once|include)(\s*)(["\'])(\.\.?/[^"\']+)(["\'])(\s*;)', re.MULTILINE)
updated = []
for path in root.rglob('*.php'):
    text = path.read_text(encoding='utf-8')
    def repl(m):
        prefix, kw, spacing, quote, relpath, quote2, suffix = m.groups()
        if relpath.startswith('__DIR__'):
            return m.group(0)
        return f"{prefix}{kw}{spacing}__DIR__ . '{relpath}'{suffix}"
    new_text = pattern.sub(repl, text)
    if new_text != text:
        path.write_text(new_text, encoding='utf-8')
        updated.append(str(path))
print('Updated', len(updated), 'files')
for p in updated:
    print(p)
