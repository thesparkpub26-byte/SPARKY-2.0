import re
import subprocess

html_content = subprocess.run(['git', 'show', 'HEAD~2:sectionEditor/monitoring_sheet_fullscreen.html'], capture_output=True, text=True, encoding='utf-8').stdout

# Extract <style>
style_match = re.search(r'<style>(.*?)</style>', html_content, re.DOTALL)
style_content = style_match.group(1).strip() if style_match else ""

# Extract <body> inner
body_match = re.search(r'<body[^>]*>(.*?)</body>', html_content, re.DOTALL)
body_content = body_match.group(1).strip() if body_match else html_content.strip()

# Remove script tags from body
body_content = re.sub(r'<script\b[^<]*(?:(?!</script>)<[^<]*)*</script>', '', body_content, flags=re.DOTALL)

# Replace asset paths
body_content = body_content.replace('../Assets/Spark_Logo.png', '/assets/Spark_Logo.png')
body_content = body_content.replace('../Assets/White_Spark_Logo.png', '/assets/White_Spark_Logo.png')

# Replace inline onclick modal triggers with Vue reactive bindings
body_content = body_content.replace("onclick=\"openModal('addTaskModal')\"", "@click.prevent=\"isAddTaskModalOpen = true\"")
body_content = body_content.replace("onclick=\"closeModal('addTaskModal')\"", "@click.prevent=\"isAddTaskModalOpen = false\"")
body_content = body_content.replace("onclick=\"openModal('uploadModal')\"", "@click.prevent=\"isUploadModalOpen = true\"")
body_content = body_content.replace("onclick=\"closeModal('uploadModal')\"", "@click.prevent=\"isUploadModalOpen = false\"")

# Vue modal style bindings
body_content = body_content.replace('id="addTaskModal"', ':style="{ display: isAddTaskModalOpen ? \'flex\' : \'none\' }"')
body_content = body_content.replace('id="uploadModal"', ':style="{ display: isUploadModalOpen ? \'flex\' : \'none\' }"')

vue_component = f"""<template>
<div class="monitoring-page-wrapper">
{body_content}
</div>
</template>

<script setup>
import {{ ref, computed }} from 'vue';
import {{ useRouter }} from 'vue-router';

const router = useRouter();
const isAddTaskModalOpen = ref(false);
const isUploadModalOpen = ref(false);

const selectedCategory = ref('');

const articleTypesMap = {{
    'News': ['Full News', 'Special Report', 'News Bit', 'News Feature', 'In-Depth News'],
    'Op-Ed': ['Opinion', 'Spark Agent', 'Letter to the Editor', 'Editorial'],
    'Feature': ['SciTech', 'General Feature'],
    'DevCom': ['Feature-Style'],
    'Sports': ['News', 'News Feature', 'Opinion'],
    'Literary': ['Poem/Tula', 'Flash Fiction/Dagli', 'Short Story/Maikling Kwento', 'Screenplay']
}};

const availableArticleTypes = computed(() => {{
    if (!selectedCategory.value) return [];
    return articleTypesMap[selectedCategory.value] || [];
}});

const goBack = () => {{
    if (window.history.length > 1) {{
        router.back();
    }} else {{
        router.push('/editor');
    }}
}};
</script>

<style scoped>
.monitoring-page-wrapper {{
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    background-color: #f1f5f9;
    color: #0f172a;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}}
{style_content}
</style>
"""

with open('resources/js/views/dashboards/MonitoringSheetView.vue', 'w', encoding='utf-8') as f:
    f.write(vue_component)

print("MonitoringSheetView.vue written successfully!")
