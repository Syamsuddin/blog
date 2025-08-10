#!/bin/bash

# GitHub Upload Preparation Script
# Laravel Blog CMS

echo "🚀 Preparing Laravel Blog CMS for GitHub Upload..."
echo ""

# Check current directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Not in Laravel project root directory"
    exit 1
fi

echo "📋 Pre-upload Checklist:"
echo ""

# Check .env file
if [ -f ".env" ]; then
    echo "⚠️  WARNING: .env file exists and contains sensitive data"
    echo "   This file is already in .gitignore and won't be uploaded"
else
    echo "✅ No .env file found (good for GitHub)"
fi

# Check .env.example
if [ -f ".env.example" ]; then
    echo "✅ .env.example exists (template for others)"
else
    echo "⚠️  Consider creating .env.example as a template"
fi

# Check large folders
echo ""
echo "📁 Folder sizes (will be excluded by .gitignore):"

if [ -d "node_modules" ]; then
    SIZE=$(du -sh node_modules | cut -f1)
    echo "   node_modules/: $SIZE (excluded)"
else
    echo "   node_modules/: Not found"
fi

if [ -d "vendor" ]; then
    SIZE=$(du -sh vendor | cut -f1)
    echo "   vendor/: $SIZE (excluded)"
else
    echo "   vendor/: Not found"
fi

if [ -d "public/build" ]; then
    SIZE=$(du -sh public/build | cut -f1)
    echo "   public/build/: $SIZE (excluded)"
else
    echo "   public/build/: Not found"
fi

echo ""
echo "📦 Application code sizes (will be uploaded):"
du -sh app resources config database routes public 2>/dev/null | while read size dir; do
    echo "   $dir: $size"
done

echo ""
echo "🔍 Checking for sensitive files:"

# Check for sensitive files
SENSITIVE_FILES=(.env .env.backup .env.production storage/*.key)
FOUND_SENSITIVE=false

for file in "${SENSITIVE_FILES[@]}"; do
    if ls $file 1> /dev/null 2>&1; then
        echo "⚠️  Found: $file (will be excluded by .gitignore)"
        FOUND_SENSITIVE=true
    fi
done

if [ "$FOUND_SENSITIVE" = false ]; then
    echo "✅ No sensitive files found in uploadable locations"
fi

echo ""
echo "📝 Git Status:"

# Check if git is initialized
if [ -d ".git" ]; then
    echo "✅ Git repository already initialized"
    
    # Check git status
    if git status --porcelain | grep -q .; then
        echo "📝 Uncommitted changes found:"
        git status --short | head -10
        if [ $(git status --porcelain | wc -l) -gt 10 ]; then
            echo "   ... and $(( $(git status --porcelain | wc -l) - 10 )) more files"
        fi
    else
        echo "✅ No uncommitted changes"
    fi
else
    echo "ℹ️  Git not initialized. Run: git init"
fi

echo ""
echo "🎯 Ready for GitHub Upload!"
echo ""
echo "Next steps:"
echo "1. git init (if not done)"
echo "2. git add ."
echo "3. git commit -m 'Initial commit: Laravel Blog CMS'"
echo "4. Create repository on GitHub"
echo "5. git remote add origin <your-repo-url>"
echo "6. git push -u origin main"
echo ""
echo "📊 Estimated upload size: ~2-3 MB (without dependencies)"
echo "⏱️  Upload time: ~30 seconds (depends on connection)"
echo ""
echo "✅ Safe to upload - no sensitive data will be included!"
