<template>
  <div>
    <v-dialog v-model="dialog" fullscreen persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-hover v-slot="{ hover }">
          <v-card
            class="portrait"
            tile
            :img="value"
            :height="height"
            width="100%"
            v-bind="attrs"
            v-on="on"
          >
            <v-expand-transition>
              <div
                v-if="hover"
                class="image-selector-icon d-flex justify-center align-center fill-height"
              >
                <v-btn color="info" fab>
                  <v-icon x-large>
                    mdi-folder-outline
                  </v-icon>
                </v-btn>
              </div>
            </v-expand-transition>
          </v-card>
        </v-hover>
      </template>
      <v-card
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="handleFileDrop"
        style="position: relative"
      >
        <v-fade-transition>
          <div
            class="image-selector-drag d-flex justify-center align-center"
            v-if="dragging"
          >
            <v-icon x-large dark>
              mdi-paperclip
            </v-icon>
          </div>
        </v-fade-transition>

        <v-toolbar dense color="info" class="px-2">
          <div class="text-h5 white--text">
            Kuvavalinta /{{ selected || "" }}
          </div>
          <v-spacer />
          <v-btn icon small @click="dialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar>

        <div class="d-flex">
          <v-list nav>
            <v-list-item-group v-model="selected" color="info">
              <v-list-item
                v-for="(folder, i) in folders"
                :key="i"
                :value="folder"
              >
                <template v-slot:default="{ active }">
                  <v-list-item-icon class="mr-2">
                    <v-icon>
                      {{ active ? "mdi-folder-open" : "mdi-folder" }}
                    </v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>{{ folder }}</v-list-item-title>
                  </v-list-item-content>
                </template>
              </v-list-item>
            </v-list-item-group>
          </v-list>

          <div class="pa-4 flex-grow-1">
            <v-form
              class="d-flex flex-wrap mx-2 align-center"
              @submit.prevent="uploadFiles()"
              ref="uploadForm"
            >
              <v-file-input
                show-size
                multiple
                accept="image/*"
                label="File input"
                @change="setFiles"
              />
              <v-btn
                class="ml-6"
                type="submit"
                color="success"
                :disabled="!hasFiles"
              >
                Lähetä
              </v-btn>
            </v-form>
            <div class="d-flex flex-wrap">
              <v-card
                class="ma-2"
                v-for="(img, id) in files"
                :key="id"
                @click="imageSelected(img)"
                width="200"
                height="136"
                :img="img"
                @contextmenu.prevent="showContextMenu"
                :data-path="img"
              />

              <v-menu
                v-model="contextMenu"
                :position-x="contextMenuPos['x']"
                :position-y="contextMenuPos['y']"
                absolute
                offset-y
                dark
              >
                <v-list dense>
                  <v-list-item @click="deleteFile">
                    <v-list-item-icon class="mr-1">
                      <v-icon color="error">mdi-close</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Delete</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
          </div>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import httpClient from "@/mixins/httpClient";
import { mapActions } from "vuex";

export default {
  props: ["height", "value"],
  mixins: [httpClient],

  data: () => ({
    dialog: false,
    dragging: false,

    selected: null,
    files: null,

    contextMenu: false,
    contextMenuPos: { x: 0, y: 0 },
    contextMenuTarget: null,

    folders: [],

    inputFiles: [],
  }),

  computed: {
    hasFiles() {
      return this.inputFiles?.length;
    },
  },

  methods: {
    imageSelected(img) {
      this.$emit("input", img);
      this.dialog = false;
    },

    setFiles(files) {
      this.inputFiles = files;
    },

    uploadFiles(files) {
      const upFiles = files ? files : this.inputFiles;

      const data = new FormData();
      for (const file of upFiles) {
        data.append("files[]", file, file.name);
      }

      this.http(`api/admin/images/${this.selected || "root"}`, {
        method: "POST",
        headers: {},
        body: data,
      })
        .then((resp) => {
          this.files = resp.data;
          this.showMessage({ message: resp.message, color: "success" });
        })
        .catch((err) => this.showMessage({ message: err, color: "error" }));

      this.$refs.uploadForm.reset();
    },

    deleteFile() {
      this.http("api/admin/images", {
        method: "DELETE",
        body: JSON.stringify({ path: this.contextMenuTarget.dataset.path }),
      })
        .then((resp) => {
          this.showMessage({ message: resp.message, color: "success" });
          this.getFiles(this.selected || "root");
        })
        .catch((err) => this.showMessage({ message: err, color: "error" }));
    },

    getFolders() {
      this.http("api/admin/images/folders").then((resp) => {
        this.folders = resp.data;
      });
    },

    getFiles(folder) {
      this.http(`api/admin/images/${folder}`)
        .then((resp) => (this.files = resp.data))
        .catch((err) => console.error(err));
    },

    handleFileDrop(event) {
      this.dragging = false;
      this.uploadFiles(event.dataTransfer.files);
    },

    showContextMenu(event) {
      this.contextMenu = false;
      this.contextMenuPos = { x: event.clientX, y: event.clientY };
      this.contextMenuTarget = event.target;

      this.$nextTick(() => {
        this.contextMenu = true;
      });
    },

    ...mapActions(["showMessage"]),
  },

  watch: {
    dialog(val) {
      if (val) {
        this.getFolders();
        this.getFiles("root");
      } else {
        this.selected = null;
        this.files = null;
      }
    },
    selected() {
      this.files = null;
      this.getFiles(this.selected || "root");
    },
  },
};
</script>

<style lang="scss">
.image-selector-drag {
  position: absolute;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  background: rgba($color: #000000, $alpha: 0.3);
  z-index: 20;
}

.image-selector-icon {
  background: rgba($color: #000000, $alpha: 0.3);
}
</style>
