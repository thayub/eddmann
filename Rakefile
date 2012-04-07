task :default => [:dev]

task :dev do
  puts 'Mode: development'
  edit_config('mode', 'development')
  system 'jekyll --server --auto'
end

task :prod do
  puts 'Mode: production'
  edit_config('mode', 'production')
  puts 'Generating site...'
  system 'jekyll'
  puts 'Compressing assets...'
  system 'jammit -o _site/assets/css -c _assets.yml'
end

task :deploy do
  system(%{
    rsync -avz --delete _site/ user@eddmann.com:/location
  })
end

def edit_config(option_name, value)
  config = File.read("_config.yml")
  regexp = Regexp.new('(^\s*' + option_name + '\s*:\s*)(\S+)(\s*)$')
  config.sub!(regexp,'\1'+value+'\3')
  File.open("_config.yml", 'w') {|f| f.write(config)}
end