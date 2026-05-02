# Zone7 Team Members

Zone7 Team Members is a lightweight and flexible WordPress plugin designed to help you manage and display your team members with ease. It features a custom post type for team member profiles and a powerful shortcode for displaying them on any page or post.

## Installation Instructions

1. **Upload the Plugin:**
   - Download the plugin as a ZIP file.
   - Go to your WordPress Dashboard, navigate to **Plugins > Add New**.
   - Click **Upload Plugin** and select the ZIP file.
   - Alternatively, unzip the file and upload the \`zone-team-members\` folder to your \`/wp-content/plugins/\` directory via FTP.

2. **Activate the Plugin:**
   - Once uploaded, click **Activate** on the Plugins page.

3. **Install Dependencies (For Developers):**
   - If you are working in a development environment, navigate to the plugin directory and run:
     \`\`\`bash
     composer install
     \`\`\`

## Usage Instructions

### Adding Team Members
- After activation, a new menu item **Team Members** will appear in your dashboard.
- Click **Add New** to create a team member profile.
- You can add the team member's name (title), position, biography, and a profile picture.

### Using the Shortcode
To display your team members, use the \`[team_members]\` shortcode in any page, post, or widget area.

#### Shortcode Parameters:
- \`number\`: (Integer) The total number of team members to display. (Default: \`8\`)
- \`image_position\`: (String) The layout of the image relative to the content. Options: \`top\`, \`bottom\`. (Default: \`top\`)
- \`show_all_button\`: (Boolean) Whether to display the "See All" button that loads all remaining members via AJAX. (Default: \`true\`)

**Example:**
\`[team_members number="4" image_position="bottom" show_all_button="false"]\`

## Design Selection

The plugin offers two distinct layout designs for team member items, controlled by the \`image_position\` parameter:

### Top Layout (\`image_position="top"\`)
This is the default layout. The team member's profile picture is displayed prominently at the top of the card, followed by their name, position, and bio. This is ideal for a classic, clean grid look.

### Bottom Layout (\`image_position="bottom"\`)
In this layout, the image is moved below the name and position. This provides a modern alternative that emphasizes the team member's details first.

## Bonus Features

### Dummy Data Import
Need a quick start? Zone7 Team Members comes with a built-in dummy data importer to help you visualize your layouts instantly.

- Go to **Team Members > Dummy Data**.
- Click the **IMPORT DATA** button to automatically create 12 sample team member profiles complete with positions, bios, and placeholder images.
- You can also remove all dummy data at any time by clicking the **REMOVE DATA** button.

---
*Created with ❤️ by Shakhawat*
