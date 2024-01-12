<?php

/**
 * PHP-GTK Cookbook
 *
 * https://andor.com.br/php-gtk/cook/my-first-php-gtk-program
 */

\Gtk::init();

// create and configure window
$window = new \GtkWindow();
$window->set_size_request(800, 600);
$window->set_position(\Gtk::WIN_POS_CENTER);
$window->set_title("PHP-GTK");

// connect window to signal destroy
$window->connect("destroy", function() {
    \Gtk::main_quit();
});

// create the GtkBox layout
$vbox = new \GtkBox(GtkOrientation::VERTICAL);

// Create a label to layout
$label = new \GtkLabel("Name");
$label->set_xalign(0);
$vbox->pack_start($label, FALSE, TRUE, 0);

// Create a GtkEntry to layout
$entry = new \GtkEntry();
$vbox->pack_start($entry, FALSE, TRUE, 0);

// Create a GtkButton to layout
$button = \GtkButton::new_with_label("add name");
$vbox->pack_start($button, FALSE, TRUE, 0);

// Create a GtkTreeView
$scroll = new GtkScrolledWindow();
$treeView = new GtkTreeView();
$scroll->add($treeView);
$vbox->pack_start($scroll, TRUE, TRUE, 0);

// column
$column1 = new GtkTreeViewColumn("Name", new GtkCellRendererText(), "text", 0);
$treeView->append_column($column1);

// create model
$model = new GtkListStore(GObject::TYPE_STRING);
$treeView->set_model($model);

// connect button to signal clicked, called when click on button
$button->connect("clicked", function($widget) use ($entry, $model, $window) {

    // get name added on GtkEntry
    $name = $entry->get_text();
    if(strlen($name) > 0) {

        // add name to the model
        $model->append([$name]);

        // clear the entry
        $entry->set_text("");
    }
    else {
        $dialog = GtkMessageDialog::new_with_markup(
            $window,
            GtkDialogFlags::MODAL,
            GtkMessageType::INFO,
            GtkButtonsType::OK,
            "you need to add a valid name"
        );
        $dialog->run();
        $dialog->destroy();
    }

});

// create an image
$image = new GtkImage();
$image->set_from_file("test.png");
$vbox->pack_start($image, FALSE, TRUE, 0);

// add box to window
$window->add($vbox);
$window->show_all();

\Gtk::main();