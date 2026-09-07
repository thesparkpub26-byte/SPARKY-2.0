import subprocess
import re

def get_git_file(path):
    result = subprocess.run(["git", "show", f"HEAD~2:{path}"], capture_output=True, text=True, encoding="utf-8")
    return result.stdout

def extract_body_inner(html_content):
    match = re.search(r'<body[^>]*>(.*?)</body>', html_content, re.DOTALL | re.IGNORECASE)
    if match:
        body = match.group(1)
        body_clean = re.sub(r'<script\b[^<]*(?:(?!</script>)<[^<]*)*</script>', '', body, flags=re.DOTALL | re.IGNORECASE)
        return body_clean.strip()
    return html_content.strip()

def clean_asset_paths(content):
    content = content.replace('../Assets/Spark_Logo.png', '/assets/Spark_Logo.png')
    content = content.replace('../Assets/White_Spark_Logo.png', '/assets/White_Spark_Logo.png')
    content = content.replace('../Assets/Fractals.png', '/assets/Fractals.png')
    content = content.replace('href="../authentication/login.html"', '@click.prevent="$router.push(\'/login\')" href="#"')
    content = content.replace('onclick="window.location.href=\'../authentication/login.html\'"', '@click.prevent="$router.push(\'/login\')"')
    return content

def convert_dashboard(git_path):
    html = get_git_file(git_path)
    body = extract_body_inner(html)
    body = clean_asset_paths(body)
    
    # Replace data-target="xyz" with @click.prevent="activeTab = 'xyz'" :class="{ active: activeTab === 'xyz' }"
    def repl_nav(match):
        target = match.group(1)
        return f'@click.prevent="activeTab = \'{target}\'" :class="{{ active: activeTab === \'{target}\' }}"'
    
    body = re.sub(r'data-target="([^"]+)"', repl_nav, body)

    # Replace <section id="section-xyz" class="content-section..."> with v-show="activeTab === 'xyz'"
    def repl_section(match):
        target = match.group(1)
        rest_cls = match.group(2)
        return f'<section id="section-{target}" class="content-section{rest_cls}" v-show="activeTab === \'{target}\'"'
    
    body = re.sub(r'<section id="section-([^"]+)" class="content-section([^"]*)"', repl_section, body)
    return body

print("Generating AdminDashboard.vue...")
admin_body = convert_dashboard("admin/admin.html")
with open("resources/js/views/dashboards/AdminDashboard.vue", "w", encoding="utf-8") as f:
    f.write(f"<template>\n{admin_body}\n</template>\n\n<script setup>\nimport {{ ref }} from 'vue';\nconst activeTab = ref('overview');\n</script>\n")

print("Generating EditorInChiefDashboard.vue...")
eic_body = convert_dashboard("editorInChief/editorInChief.html")
with open("resources/js/views/dashboards/EditorInChiefDashboard.vue", "w", encoding="utf-8") as f:
    f.write(f"<template>\n{eic_body}\n</template>\n\n<script setup>\nimport {{ ref }} from 'vue';\nconst activeTab = ref('overview');\n</script>\n")

print("Generating StaffWriterDashboard.vue...")
writer_body = convert_dashboard("staffWriter/staffWriter.html")
with open("resources/js/views/dashboards/StaffWriterDashboard.vue", "w", encoding="utf-8") as f:
    f.write(f"<template>\n{writer_body}\n</template>\n\n<script setup>\nimport {{ ref }} from 'vue';\nconst activeTab = ref('tasks');\n</script>\n")

print("Generating StaffArtistDashboard.vue...")
artist_body = convert_dashboard("staffArtist/staffArtist.html")
with open("resources/js/views/dashboards/StaffArtistDashboard.vue", "w", encoding="utf-8") as f:
    f.write(f"<template>\n{artist_body}\n</template>\n\n<script setup>\nimport {{ ref }} from 'vue';\nconst activeTab = ref('tasks');\n</script>\n")

print("Generating SectionEditorDashboard.vue...")
editor_html = get_git_file("sectionEditor/sectionEditor.html")
sec_overview = extract_body_inner(get_git_file("sectionEditor/sectionEditor_overview.html"))
sec_articles = extract_body_inner(get_git_file("sectionEditor/sectionEditor_articles.html"))
sec_press = extract_body_inner(get_git_file("sectionEditor/sectionEditor_pressWorks.html"))
sec_contrib = extract_body_inner(get_git_file("sectionEditor/contributors.html"))
sec_workflow = extract_body_inner(get_git_file("sectionEditor/workflow.html"))

editor_body = extract_body_inner(editor_html)
editor_body = clean_asset_paths(editor_body)

subviews_html = f"""
<div id="main-content-container" class="content-container fade-in">
    <div v-show="activeTab === 'overview'">
        {sec_overview}
    </div>
    <div v-show="activeTab === 'articles'">
        {sec_articles}
    </div>
    <div v-show="activeTab === 'press-works'">
        {sec_press}
    </div>
    <div v-show="activeTab === 'contributors'">
        {sec_contrib}
    </div>
    <div v-show="activeTab === 'workflow'">
        {sec_workflow}
    </div>
</div>
"""

editor_body = re.sub(r'<div id="main-content-container"[^>]*>.*?</div>', subviews_html, editor_body, flags=re.DOTALL)
editor_body = editor_body.replace('data-page="sectionEditor_overview.html"', '@click.prevent="activeTab = \'overview\'" :class="{ active: activeTab === \'overview\' }"')
editor_body = editor_body.replace('data-page="sectionEditor_articles.html"', '@click.prevent="activeTab = \'articles\'" :class="{ active: activeTab === \'articles\' }"')
editor_body = editor_body.replace('data-page="sectionEditor_pressWorks.html"', '@click.prevent="activeTab = \'press-works\'" :class="{ active: activeTab === \'press-works\' }"')
editor_body = editor_body.replace('data-page="contributors.html"', '@click.prevent="activeTab = \'contributors\'" :class="{ active: activeTab === \'contributors\' }"')
editor_body = editor_body.replace('data-page="workflow.html"', '@click.prevent="activeTab = \'workflow\'" :class="{ active: activeTab === \'workflow\' }"')

with open("resources/js/views/dashboards/SectionEditorDashboard.vue", "w", encoding="utf-8") as f:
    f.write(f"<template>\n{editor_body}\n</template>\n\n<script setup>\nimport {{ ref }} from 'vue';\nconst activeTab = ref('overview');\n</script>\n")

print("All dashboards generated successfully!")
